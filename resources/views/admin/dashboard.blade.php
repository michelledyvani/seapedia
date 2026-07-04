@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <span class="text-3xl">👑</span>
        <div><h1 class="text-2xl font-bold">Admin Monitoring Dashboard</h1><p class="text-sm text-base-content/60">Pantau seluruh aktivitas SEAPEDIA</p></div>
        <div class="ml-auto badge badge-warning badge-lg">ADMIN</div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Users</div><div class="stat-value text-2xl">{{ $stats['users'] }}</div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Stores</div><div class="stat-value text-2xl">{{ $stats['stores'] }}</div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Products</div><div class="stat-value text-2xl">{{ $stats['products'] }}</div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Orders</div><div class="stat-value text-2xl">{{ $stats['orders'] }}</div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Vouchers</div><div class="stat-value text-2xl">{{ $stats['vouchers'] }}</div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Promos</div><div class="stat-value text-2xl">{{ $stats['promos'] }}</div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Driver Aktif</div><div class="stat-value text-2xl">{{ $stats['active_drivers'] }}</div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border-2 border-error">
            <div class="stat-title text-error">Overdue Orders</div><div class="stat-value text-2xl text-error">{{ $stats['overdue'] }}</div>
        </div>
    </div>

    <div class="flex gap-3 flex-wrap mb-6">
        <a href="{{ route('admin.orders') }}" class="btn btn-outline btn-sm">📋 Semua Order</a>
        <a href="{{ route('admin.users') }}" class="btn btn-outline btn-sm">👥 Semua User</a>
        <a href="{{ route('admin.discounts') }}" class="btn btn-outline btn-sm">🎟 Kelola Diskon</a>
    </div>

    {{-- SIMULATE NEXT DAY --}}
    <div class="card bg-base-100 border-2 border-warning shadow mb-6">
        <div class="card-body">
            <h2 class="card-title">⏰ Simulasi Hari Berikutnya</h2>
            <p class="text-sm text-base-content/70">
                Trigger ini akan mencari semua order yang sudah melewati SLA pengiriman (Instant: 1 hari, Next Day: 2 hari, Regular: 7 hari)
                dan belum selesai/dikembalikan, lalu otomatis: mengembalikan saldo ke wallet buyer, mengembalikan stok produk,
                dan mengubah status order menjadi <strong>Dikembalikan</strong>.
            </p>
            @if(count($overdueOrders) > 0)
                <div class="alert alert-warning text-sm mt-2">
                    Ditemukan <strong>{{ count($overdueOrders) }}</strong> order yang overdue dan siap diproses.
                </div>
                <div class="overflow-x-auto mt-2">
                    <table class="table table-sm">
                        <thead><tr><th>Order</th><th>Buyer</th><th>Toko</th><th>Total</th><th>Deadline</th></tr></thead>
                        <tbody>
                            @foreach($overdueOrders as $o)
                            <tr>
                                <td>#{{ $o->id }}</td><td>{{ $o->buyer->name }}</td><td>{{ $o->store->name }}</td>
                                <td>Rp {{ number_format($o->total_amount,0,',','.') }}</td>
                                <td class="text-error">{{ $o->overdue_at->format('d M Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-success mt-2">✅ Tidak ada order overdue saat ini.</p>
            @endif
            <form action="{{ route('admin.simulate') }}" method="POST" class="mt-3" onsubmit="return confirm('Jalankan simulasi hari berikutnya? Ini akan memproses semua order overdue.')">
                @csrf
                <button class="btn btn-warning" {{ count($overdueOrders) === 0 ? 'disabled' : '' }}>
                    ⏭ Simulate Next Day
                </button>
            </form>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-3">Order Terbaru</h2>
    <div class="overflow-x-auto">
        <table class="table bg-base-100 shadow rounded-xl">
            <thead><tr><th>Order</th><th>Buyer</th><th>Toko</th><th>Status</th><th>Total</th></tr></thead>
            <tbody>
                @foreach($recentOrders as $o)
                <tr>
                    <td>#{{ $o->id }}</td><td>{{ $o->buyer->name }}</td><td>{{ $o->store->name }}</td>
                    <td><span class="badge badge-sm">{{ $o->status }}</span></td>
                    <td>Rp {{ number_format($o->total_amount,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
