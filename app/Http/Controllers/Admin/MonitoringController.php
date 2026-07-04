<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Store, Product, Order, Voucher, Promo};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonitoringController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users'          => User::count(),
            'stores'         => Store::count(),
            'products'       => Product::count(),
            'orders'         => Order::count(),
            'vouchers'       => Voucher::count(),
            'promos'         => Promo::count(),
            'active_drivers' => Order::where('status','Sedang Dikirim')->whereNotNull('driver_id')->distinct('driver_id')->count(),
            'overdue'        => Order::whereNotIn('status',['Pesanan Selesai','Dikembalikan'])
                                    ->where('overdue_at','<', Carbon::now())
                                    ->count(),
        ];
        $recentOrders    = Order::with('buyer','store')->latest()->take(10)->get();
        $overdueOrders   = Order::whereNotIn('status',['Pesanan Selesai','Dikembalikan'])
                                ->where('overdue_at','<', Carbon::now())
                                ->where('refunded', false)
                                ->with('buyer','store')
                                ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'overdueOrders'));
    }

    public function simulateNextDay()
    {
        $now = Carbon::now();

        $overdueOrders = Order::whereNotIn('status', ['Pesanan Selesai', 'Dikembalikan'])
                              ->where('overdue_at', '<', $now)
                              ->where('refunded', false)
                              ->with('items.product', 'buyer.wallet')
                              ->get();

        $processed = 0;

        foreach ($overdueOrders as $order) {
            DB::transaction(function () use ($order) {
                // Kembalikan saldo buyer ke ewallet 
                $wallet = $order->buyer->getOrCreateWallet();
                $wallet->refund($order->total_amount, 'Auto-refund Order #' . $order->id . ' (overdue)');

                // Kembalikan stok produk
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }

                // Update status order
                $order->update(['refunded' => true]);
                $order->updateStatus('Dikembalikan', 'Auto-refund oleh sistem karena melewati SLA.');
            });

            $processed++;
        }

        return redirect()->route('admin.dashboard')
            ->with('success', "Simulasi selesai. {$processed} order direfund otomatis.");
    }

    public function orders()
    {
        $orders = Order::with('buyer','store','driver')->latest()->paginate(20);
        return view('admin.orders', compact('orders'));
    }

    public function users()
    {
        $users = User::with('userRoles')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }
}
