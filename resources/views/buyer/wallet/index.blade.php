@extends('layouts.app')
@section('title', 'Wallet')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">💰 Wallet Saya</h1>

    <div class="card bg-gradient-to-r from-neutral to-neutral-focus text-neutral-content shadow mb-6">
        <div class="card-body">
            <p class="text-sm opacity-70">Saldo Saat Ini</p>
            <p class="text-4xl font-extrabold text-warning">Rp {{ number_format($wallet->balance,0,',','.') }}</p>
        </div>
    </div>

    <div class="card bg-base-100 border border-base-200 shadow mb-6">
        <div class="card-body">
            <h2 class="card-title text-lg">Top Up Saldo</h2>
            <p class="text-xs text-base-content/50 mb-2">Simulasi top-up tanpa payment gateway sungguhan.</p>
            @if($errors->any())<div class="alert alert-error text-sm">{{ $errors->first() }}</div>@endif
            <form action="{{ route('buyer.wallet.topup') }}" method="POST" class="flex gap-2 flex-wrap">
                @csrf
                <div class="flex gap-2 flex-wrap">
                    @foreach([50000,100000,250000,500000,1000000] as $amt)
                    <button type="submit" name="amount" value="{{ $amt }}" class="btn btn-outline btn-sm">
                        Rp {{ number_format($amt,0,',','.') }}
                    </button>
                    @endforeach
                </div>
            </form>
            <form action="{{ route('buyer.wallet.topup') }}" method="POST" class="flex gap-2 mt-3">
                @csrf
                <input type="number" name="amount" placeholder="Jumlah custom" class="input input-bordered input-sm flex-1" min="10000" />
                <button type="submit" class="btn btn-warning btn-sm">Top Up</button>
            </form>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-3">Riwayat Transaksi</h2>
    @if($transactions->isEmpty())
        <p class="text-base-content/50 text-sm">Belum ada transaksi.</p>
    @else
        <div class="space-y-2">
            @foreach($transactions as $t)
            <div class="card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body py-3 flex-row justify-between items-center">
                    <div>
                        <p class="font-semibold text-sm">{{ $t->description }}</p>
                        <p class="text-xs text-base-content/50">{{ $t->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <p class="font-bold {{ $t->type === 'payment' ? 'text-error' : 'text-success' }}">
                        {{ $t->type === 'payment' ? '-' : '+' }}Rp {{ number_format($t->amount,0,',','.') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $transactions->links() }}</div>
    @endif
</div>
@endsection
