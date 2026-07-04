@extends('layouts.app')
@section('title', 'Cari Lowongan')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">🔍 Lowongan Pengiriman Tersedia</h1>

    @if($jobs->isEmpty())
        <div class="text-center py-16"><p class="text-4xl mb-3">📭</p><p class="font-semibold">Belum ada lowongan tersedia saat ini</p><p class="text-sm text-base-content/60">Cek lagi nanti, atau seller mungkin belum memproses pesanan.</p></div>
    @else
        <div class="space-y-4">
            @foreach($jobs as $job)
            <div class="card bg-base-100 border border-base-200 shadow">
                <div class="card-body">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="font-bold">Order #{{ $job->id }} — {{ $job->store->name }}</p>
                            <p class="text-sm text-base-content/60">Tujuan: {{ $job->address->city }}, {{ $job->address->province }}</p>
                            <p class="text-xs text-base-content/50">{{ ucfirst($job->delivery_method) }} · {{ $job->items->count() }} item</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-base-content/60">Pendapatanmu</p>
                            <p class="font-bold text-success">Rp {{ number_format($job->delivery_fee,0,',','.') }}</p>
                        </div>
                    </div>
                    <div class="card-actions mt-3">
                        <a href="{{ route('driver.jobs.show', $job) }}" class="btn btn-outline btn-sm">Lihat Detail</a>
                        <form action="{{ route('driver.jobs.take', $job) }}" method="POST" onsubmit="return confirm('Ambil pesanan ini?')">
                            @csrf
                            <button class="btn btn-warning btn-sm">🚚 Ambil Pesanan Ini</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $jobs->links() }}</div>
    @endif
</div>
@endsection
