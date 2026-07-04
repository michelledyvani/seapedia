<?php
namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    public function index()
    {
        $cart  = $this->getCart();
        $items = $cart->items()->with('product.store')->get();
        return view('buyer.cart.index', compact('cart', 'items'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart    = $this->getCart();

        // Single-store rule
        if ($cart->store_id && $cart->store_id !== $product->store_id) {
            return back()->with('error',
                '⚠️ Keranjangmu sudah berisi produk dari toko "' . optional($cart->store)->name . '". ' .
                'SEAPEDIA hanya mendukung 1 toko per checkout. Kosongkan keranjang dulu.'
            );
        }

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak cukup.');
        }

        if (!$cart->store_id) $cart->update(['store_id' => $product->store_id]);

        $existing = CartItem::where('cart_id', $cart->id)->where('product_id', $product->id)->first();

        if ($existing) {
            $newQty = $existing->quantity + $request->quantity;
            if ($product->stock < $newQty) return back()->with('error', 'Stok tidak cukup untuk jumlah tersebut.');
            $existing->update(['quantity' => $newQty]);
        } else {
            CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => $request->quantity]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function updateItem(Request $request, CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) abort(403);
        $request->validate(['quantity' => 'required|integer|min:1']);
        if ($cartItem->product->stock < $request->quantity) return back()->with('error', 'Stok tidak cukup.');
        $cartItem->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Jumlah diperbarui.');
    }

    public function removeItem(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== Auth::id()) abort(403);
        $cart = $cartItem->cart;
        $cartItem->delete();
        if ($cart->items()->count() === 0) $cart->update(['store_id' => null]);
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        $cart->update(['store_id' => null]);
        return back()->with('success', 'Keranjang dikosongkan.');
    }
}
