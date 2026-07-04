@extends('layouts.app')
@section('title', 'Detail Order #' . $order->id)
@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-2">Order #{{ $order->id }}</h1>
    <p class="text-sm text-base-content/60 mb-6">{{ $order->store->name }} · {{ $order->created_at->format('d M Y H:i') }}</p>

    <div class="card bg-base-100 border border-base-200 shadow mb-4">
        <div class="card-body">
            <h2 class="card-title text-base">Status Pesanan</h2>
            <ul class="steps steps-vertical">
                @foreach($order->statusHistories as $h)
                <li class="step step-warning">
                    {{ $h->status }}
                    <span class="block text-xs text-base-content/50">{{ $h->created_at->format('d M Y H:i') }} — {{ $h->note }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    @if($order->driver)
    <div class="card bg-base-100 border border-base-200 shadow mb-4">
        <div class="card-body">
            <h2 class="card-title text-base">🚚 Driver</h2>
            <p class="text-sm">{{ $order->driver->name }}</p>
        </div>
    </div>
    @endif

    <div class="card bg-base-100 border border-base-200 shadow mb-4">
        <div class="card-body">
            <h2 class="card-title text-base">Item Pesanan</h2>
            @foreach($order->items as $item)
            <div class="flex justify-between text-sm py-1 border-b border-base-200 last:border-0">
                <span>{{ $item->product_name }} ×{{ $item->quantity }}</span>
                <span>Rp {{ number_format($item->subtotal,0,',','.') }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="card bg-base-100 border border-base-200 shadow mb-4">
        <div class="card-body">
            <h2 class="card-title text-base">Rincian Pembayaran</h2>
            <div class="text-sm space-y-1">
                <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal,0,',','.') }}</span></div>
                @if($order->discount_amount > 0)
                <div class="flex justify-between text-success"><span>Diskon @if($order->voucher_code)({{ $order->voucher_code }})@endif @if($order->promo_code)({{ $order->promo_code }})@endif</span><span>-Rp {{ number_format($order->discount_amount,0,',','.') }}</span></div>
                @endif
                <div class="flex justify-between"><span>PPN 12%</span><span>Rp {{ number_format($order->tax_amount,0,',','.') }}</span></div>
                <div class="flex justify-between"><span>Ongkos Kirim ({{ ucfirst($order->delivery_method) }})</span><span>Rp {{ number_format($order->delivery_fee,0,',','.') }}</span></div>
                <div class="divider my-1"></div>
                <div class="flex justify-between font-bold text-base"><span>Total</span><span class="text-warning">Rp {{ number_format($order->total_amount,0,',','.') }}</span></div>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 border border-base-200 shadow">
        <div class="card-body">
            <h2 class="card-title text-base">📍 Alamat Pengiriman</h2>
            <p class="text-sm">{{ $order->address->recipient_name }} · {{ $order->address->phone }}</p>
            <p class="text-sm text-base-content/70">{{ $order->address->full_address }}, {{ $order->address->city }}, {{ $order->address->province }}</p>
        </div>
    </div>
</div>
@endsection
