@extends('layouts.app')
@section('title', 'Monitoring Order')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">📋 Semua Order</h1>
    <div class="overflow-x-auto">
        <table class="table bg-base-100 shadow rounded-xl">
            <thead><tr><th>ID</th><th>Buyer</th><th>Toko</th><th>Driver</th><th>Status</th><th>Total</th><th>Deadline SLA</th></tr></thead>
            <tbody>
                @foreach($orders as $o)
                @php
                    $badgeClass = match($o->status) {
                        'Sedang Dikemas'=>'badge-warning','Menunggu Pengirim'=>'badge-info','Sedang Dikirim'=>'badge-primary',
                        'Pesanan Selesai'=>'badge-success','Dikembalikan'=>'badge-error', default=>'badge-ghost',
                    };
                    $isOverdue = $o->overdue_at && $o->overdue_at->isPast() && !in_array($o->status, ['Pesanan Selesai','Dikembalikan']);
                @endphp
                <tr class="{{ $isOverdue ? 'bg-error/10' : '' }}">
                    <td>#{{ $o->id }}</td>
                    <td>{{ $o->buyer->name }}</td>
                    <td>{{ $o->store->name }}</td>
                    <td>{{ $o->driver->name ?? '-' }}</td>
                    <td><span class="badge {{ $badgeClass }}">{{ $o->status }}</span> @if($isOverdue)<span class="badge badge-error badge-xs ml-1">OVERDUE</span>@endif</td>
                    <td>Rp {{ number_format($o->total_amount,0,',','.') }}</td>
                    <td class="text-xs">{{ $o->overdue_at?->format('d M H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
