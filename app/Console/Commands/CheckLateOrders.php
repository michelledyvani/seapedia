<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckLateOrders extends Command
{
    
    protected $signature = 'orders:check-late';

    
    protected $description = 'Find orders that exceeded their delivery SLA and auto-refund them';

    /**
     * Execute the console command.
     *
     * Flow:
     * 1. Query all active orders whose overdue_at timestamp is in the past
     *    and that have not yet been refunded.
     * 2. For each late order, wrap the following steps in a DB transaction
     *    to ensure atomicity (all-or-nothing):
     *    a) Change order status to 'Dikembalikan'
     *    b) Refund the total_amount back to the buyer's Wallet
     *    c) Restore product stock quantities from the order items
     *    d) Deduct the earnings (subtotal) from the seller's Wallet balance
     */
    public function handle(): int
    {
        $now = Carbon::now();

        // Step 1: Find all orders that have exceeded their delivery SLA.
        // An order is considered "late" when:
        // - Its status is NOT already completed or returned
        // - Its overdue_at timestamp is in the past
        // - It has not been refunded yet (prevents double-processing)
        $lateOrders = Order::whereNotIn('status', ['Pesanan Selesai', 'Dikembalikan'])
            ->where('overdue_at', '<', $now)
            ->where('refunded', false)
            ->with(['items.product', 'buyer', 'seller'])
            ->get();

        if ($lateOrders->isEmpty()) {
            $this->info('No late orders found.');
            return self::SUCCESS;
        }

        $this->info("Found {$lateOrders->count()} late order(s). Processing...");

        $processed = 0;
        $failed    = 0;

        foreach ($lateOrders as $order) {
            try {
                // Step 2: Wrap all mutations in a transaction so that if any
                // step fails, none of the changes are persisted. This prevents
                // partial refunds or inconsistent stock/wallet states.
                DB::transaction(function () use ($order) {

                    // Step 2a: Change order status to 'Dikembalikan'.
                    // Mark the order as refunded to prevent re-processing on
                    // subsequent runs of this command (idempotency guard).
                    $order->update(['refunded' => true]);
                    $order->updateStatus('Dikembalikan', 'Auto-refund oleh sistem karena melewati batas waktu SLA.');

                    // Step 2b: Refund the full total_amount back to the buyer's wallet.
                    // This includes subtotal + tax + delivery fee − discounts,
                    // matching exactly what was deducted at checkout.
                    $buyerWallet = $order->buyer->getOrCreateWallet();
                    $buyerWallet->refund(
                        $order->total_amount,
                        'Auto-refund Order #' . $order->id . ' (melewati SLA)'
                    );

                    // Step 2c: Restore product stock quantities.
                    // Each order item's quantity is added back to the product's
                    // stock so the inventory is accurate again.
                    foreach ($order->items as $item) {
                        if ($item->product) {
                            $item->product->increment('stock', $item->quantity);
                        }
                    }

                    // Step 2d: Deduct earnings from the seller's wallet.
                    // The seller received the subtotal (product revenue before
                    // tax/delivery) when the order was placed, so we reverse
                    // that amount. We use a try-catch because the seller's
                    // wallet may not have sufficient balance (e.g., already
                    // withdrawn), in which case we still proceed — the
                    // platform can reconcile the negative balance separately.
                    $sellerWallet = $order->seller->getOrCreateWallet();
                    if ($sellerWallet->balance >= $order->subtotal) {
                        $sellerWallet->deduct(
                            $order->subtotal,
                            'Reversal pendapatan Order #' . $order->id . ' (melewati SLA)'
                        );
                    } else {
                        // Seller balance insufficient — force deduct to negative
                        // and log the transaction for admin reconciliation.
                        $sellerWallet->decrement('balance', $order->subtotal);
                        $sellerWallet->transactions()->create([
                            'type'        => 'payment',
                            'amount'      => $order->subtotal,
                            'description' => 'Reversal pendapatan Order #' . $order->id . ' (melewati SLA, saldo kurang)',
                        ]);
                    }
                });

                $processed++;
                $this->line("  ✓ Order #{$order->id} — refunded & returned.");

            } catch (\Throwable $e) {
                // If the transaction fails for any reason, log the error
                // and continue processing remaining orders.
                $failed++;
                $this->error("  ✗ Order #{$order->id} — failed: {$e->getMessage()}");
            }
        }

        $this->info("Done. Processed: {$processed}, Failed: {$failed}.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
