@extends('layouts.app')
@section('title', 'Detail Lowongan #' . $order->id)
@section('content')
<div class="max-w-2xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">📦 Lowongan Order #{{ $order->id }}</h1>

    <div class="card bg-base-100 border border-base-200 shadow mb-4">
        <div class="card-body">
            <h2 class="card-title text-base">Info Toko & Pengiriman</h2>
            <p class="text-sm">Toko: <strong>{{ $order->store->name }}</strong></p>
            <p class="text-sm">Metode: <strong>{{ ucfirst($order->delivery_method) }}</strong></p>
            <p class="text-sm">Pendapatanmu: <span class="font-bold text-success">Rp {{ number_format($order->delivery_fee,0,',','.') }}</span></p>
        </div>
    </div>

    <div class="card bg-base-100 border border-base-200 shadow mb-4">
        <div class="card-body">
            <h2 class="card-title text-base">📍 Alamat Tujuan</h2>
            <p class="text-sm">{{ $order->address->recipient_name }} · {{ $order->address->phone }}</p>
            <p class="text-sm text-base-content/70">{{ $order->address->full_address }}, {{ $order->address->city }}, {{ $order->address->province }}</p>
        </div>
    </div>

    <div class="card bg-base-100 border border-base-200 shadow mb-4">
        <div class="card-body">
            <h2 class="card-title text-base">Item ({{ $order->items->count() }})</h2>
            @foreach($order->items as $item)
            <div class="flex justify-between text-sm py-1"><span>{{ $item->product_name }} ×{{ $item->quantity }}</span></div>
            @endforeach
        </div>
    </div>

    <form action="{{ route('driver.jobs.take', $order) }}" method="POST" onsubmit="return confirm('Ambil pesanan ini?')">
        @csrf
        <button class="btn btn-warning w-full">🚚 Ambil Pesanan Ini</button>
    </form>
</div>
@endsection
