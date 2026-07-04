@extends('layouts.app')
@section('title', 'Riwayat Pesanan')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">📦 Riwayat Pesananku</h1>

    @if($orders->isEmpty())
        <div class="text-center py-16"><p class="text-4xl mb-3">📭</p><p class="font-semibold">Belum ada pesanan</p><a href="{{ route('products.index') }}" class="btn btn-warning btn-sm mt-3">Mulai Belanja</a></div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
            @php
                $badgeClass = match($order->status) {
                    'Sedang Dikemas'=>'badge-warning','Menunggu Pengirim'=>'badge-info','Sedang Dikirim'=>'badge-primary',
                    'Pesanan Selesai'=>'badge-success','Dikembalikan'=>'badge-error', default=>'badge-ghost',
                };
            @endphp
            <a href="{{ route('buyer.orders.show', $order) }}" class="card bg-base-100 border border-base-200 shadow hover:shadow-md transition-shadow">
                <div class="card-body py-4">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <p class="font-bold">Order #{{ $order->id }} — {{ $order->store->name }}</p>
                            <p class="text-xs text-base-content/50">{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <span class="badge {{ $badgeClass }}">{{ $order->status }}</span>
                    </div>
                    <p class="text-sm text-base-content/60 mt-1">{{ $order->items->count() }} item · Total: <span class="font-bold text-warning">Rp {{ number_format($order->total_amount,0,',','.') }}</span></p>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
