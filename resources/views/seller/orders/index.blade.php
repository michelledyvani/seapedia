@extends('layouts.app')
@section('title', 'Pesanan Masuk')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">📋 Pesanan Masuk — {{ $store->name }}</h1>

    @if($orders->isEmpty())
        <div class="text-center py-16"><p class="text-4xl mb-3">📭</p><p class="font-semibold">Belum ada pesanan masuk</p></div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="card bg-base-100 border border-base-200 shadow">
                <div class="card-body">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="font-bold">Order #{{ $order->id }}</p>
                            <p class="text-sm text-base-content/60">dari {{ $order->buyer->name }} · {{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                        @php
                            $badgeClass = match($order->status) {
                                'Sedang Dikemas'=>'badge-warning','Menunggu Pengirim'=>'badge-info','Sedang Dikirim'=>'badge-primary',
                                'Pesanan Selesai'=>'badge-success','Dikembalikan'=>'badge-error', default=>'badge-ghost',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $order->status }}</span>
                    </div>

                    <div class="mt-3 space-y-1">
                        @foreach($order->items as $item)
                        <div class="flex justify-between text-sm"><span>{{ $item->product_name }} ×{{ $item->quantity }}</span><span>Rp {{ number_format($item->subtotal,0,',','.') }}</span></div>
                        @endforeach
                    </div>

                    <div class="divider my-2"></div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                        <div><p class="text-base-content/60">Subtotal</p><p class="font-semibold">Rp {{ number_format($order->subtotal,0,',','.') }}</p></div>
                        @if($order->discount_amount > 0)
                        <div><p class="text-base-content/60">Diskon</p><p class="font-semibold text-success">-Rp {{ number_format($order->discount_amount,0,',','.') }}</p></div>
                        @endif
                        <div><p class="text-base-content/60">PPN 12%</p><p class="font-semibold">Rp {{ number_format($order->tax_amount,0,',','.') }}</p></div>
                        <div><p class="text-base-content/60">Total</p><p class="font-bold text-warning text-base">Rp {{ number_format($order->total_amount,0,',','.') }}</p></div>
                    </div>

                    @if($order->status === 'Sedang Dikemas')
                    <div class="card-actions mt-3">
                        <form action="{{ route('seller.orders.process', $order) }}" method="POST">
                            @csrf
                            <button class="btn btn-warning btn-sm">✅ Proses Pesanan → Menunggu Pengirim</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
