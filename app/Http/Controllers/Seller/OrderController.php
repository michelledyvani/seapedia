<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
        if (!$store) return redirect()->route('seller.store.create');
        $orders = Order::where('store_id', $store->id)->with('buyer','items','statusHistories')->latest()->paginate(10);
        return view('seller.orders.index', compact('orders', 'store'));
    }

    public function process(Order $order)
    {
        $store = Auth::user()->store;
        if (!$store || $order->store_id !== $store->id) abort(403);
        if ($order->status !== 'Sedang Dikemas') return back()->with('error', 'Status tidak valid untuk diproses.');
        $order->updateStatus('Menunggu Pengirim', 'Diproses seller ' . $store->name);
        return back()->with('success', 'Order #' . $order->id . ' siap dijemput driver!');
    }

    public function show(Order $order)
    {
        $store = Auth::user()->store;
        if (!$store || $order->store_id !== $store->id) abort(403);
        $order->load('buyer','items','address','statusHistories','driver');
        return view('seller.orders.show', compact('order'));
    }
}
