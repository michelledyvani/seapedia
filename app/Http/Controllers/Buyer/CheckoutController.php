<?php
namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\{Cart, Order, OrderItem, Address, Voucher, Promo};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};
use Carbon\Carbon;

class CheckoutController extends Controller
{
    const DELIVERY_FEES = ['instant' => 25000, 'nextday' => 15000, 'regular' => 9000];
    const DELIVERY_SLA  = ['instant' => 1,     'nextday' => 2,     'regular' => 7];
    const PPN           = 0.12;

    public function index()
    {
        $user      = Auth::user();
        $cart      = Cart::where('user_id', $user->id)->with('items.product.store')->first();
        $addresses = Address::where('user_id', $user->id)->get();
        $wallet    = $user->getOrCreateWallet();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('buyer.cart')->with('error', 'Keranjang kosong.');
        }

        return view('buyer.checkout.index', compact('cart', 'addresses', 'wallet'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'address_id'      => 'required|exists:addresses,id',
            'delivery_method' => 'required|in:instant,nextday,regular',
            'voucher_code'    => 'nullable|string|max:50',
            'promo_code'      => 'nullable|string|max:50',
        ]);

        $user    = Auth::user();
        $cart    = Cart::where('user_id', $user->id)->with('items.product')->first();
        $address = Address::where('id', $request->address_id)->where('user_id', $user->id)->firstOrFail();
        $wallet  = $user->getOrCreateWallet();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('buyer.cart')->with('error', 'Keranjang kosong.');
        }

        // Hitung subtotal
        $subtotal = 0;
        foreach ($cart->items as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', 'Stok "' . $item->product->name . '" tidak cukup.');
            }
            $subtotal += $item->product->price * $item->quantity;
        }

        $deliveryFee    = self::DELIVERY_FEES[$request->delivery_method];
        $discountAmount = 0;
        $usedVoucher    = null;
        $usedPromo      = null;

        // Validasi voucher
        if ($request->filled('voucher_code')) {
            $voucher = Voucher::where('code', strtoupper(trim($request->voucher_code)))->first();
            if (!$voucher) return back()->with('error', 'Kode voucher tidak ditemukan.');
            if (!$voucher->isValid()) return back()->with('error', 'Voucher expired atau habis digunakan.');
            $discountAmount += $voucher->calculateDiscount($subtotal);
            $usedVoucher     = $voucher;
        }

        // Validasi promo
        if ($request->filled('promo_code')) {
            $promo = Promo::where('code', strtoupper(trim($request->promo_code)))->first();
            if (!$promo) return back()->with('error', 'Kode promo tidak ditemukan.');
            if (!$promo->isValid()) return back()->with('error', 'Promo sudah expired.');
            $discountAmount += $promo->calculateDiscount($subtotal);
            $usedPromo       = $promo;
        }

        $discountAmount = min($discountAmount, $subtotal);
        $taxBase        = $subtotal - $discountAmount;
        $taxAmount      = round($taxBase * self::PPN, 2);
        $totalAmount    = $subtotal - $discountAmount + $taxAmount + $deliveryFee;

        if ($wallet->balance < $totalAmount) {
            return back()->with('error',
                'Saldo tidak cukup. Dibutuhkan: Rp ' . number_format($totalAmount, 0, ',', '.') .
                ', Saldo kamu: Rp ' . number_format($wallet->balance, 0, ',', '.'));
        }

        $store     = $cart->store;
        $seller    = $store->user;
        $overdueAt = Carbon::now()->addDays(self::DELIVERY_SLA[$request->delivery_method]);

        DB::transaction(function () use (
            $user, $cart, $address, $wallet, $store, $seller, $request,
            $subtotal, $deliveryFee, $discountAmount, $taxAmount, $totalAmount,
            $overdueAt, $usedVoucher, $usedPromo
        ) {
            $order = Order::create([
                'buyer_id'        => $user->id,
                'seller_id'       => $seller->id,
                'store_id'        => $store->id,
                'address_id'      => $address->id,
                'delivery_method' => $request->delivery_method,
                'subtotal'        => $subtotal,
                'delivery_fee'    => $deliveryFee,
                'discount_amount' => $discountAmount,
                'tax_amount'      => $taxAmount,
                'total_amount'    => $totalAmount,
                'status'          => 'Sedang Dikemas',
                'voucher_code'    => $usedVoucher?->code,
                'promo_code'      => $usedPromo?->code,
                'overdue_at'      => $overdueAt,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'product_name'  => $item->product->name,
                    'product_price' => $item->product->price,
                    'quantity'      => $item->quantity,
                    'subtotal'      => $item->product->price * $item->quantity,
                ]);
                $item->product->decrement('stock', $item->quantity);
            }

            $order->statusHistories()->create(['status' => 'Sedang Dikemas', 'note' => 'Pesanan dibuat.']);
            $wallet->deduct($totalAmount, 'Pembayaran Order #' . $order->id);

            if ($usedVoucher) $usedVoucher->markUsed();

            $cart->items()->delete();
            $cart->update(['store_id' => null]);
        });

        return redirect()->route('buyer.orders')->with('success', 'Pesanan berhasil dibuat!');
    }
}
