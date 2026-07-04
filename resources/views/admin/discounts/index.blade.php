@extends('layouts.app')
@section('title', 'Kelola Diskon')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">🎟 Kelola Voucher & Promo</h1>

    @if($errors->any())<div class="alert alert-error text-sm mb-4">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Voucher form --}}
        <div class="card bg-base-100 border border-base-200 shadow">
            <div class="card-body">
                <h2 class="card-title text-lg">Buat Voucher</h2>
                <p class="text-xs text-base-content/50">Voucher punya batas pemakaian (max_usage).</p>
                <form action="{{ route('admin.vouchers.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="text" name="code" placeholder="Kode (contoh: HEMAT50K)" class="input input-bordered w-full" required maxlength="50" />
                    <div class="grid grid-cols-2 gap-2">
                        <select name="discount_type" class="select select-bordered w-full">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                        <input type="number" name="discount_value" placeholder="Nilai" class="input input-bordered w-full" required min="1" />
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="max_usage" placeholder="Maks. Pemakaian" class="input input-bordered w-full" required min="1" />
                        <input type="datetime-local" name="expires_at" class="input input-bordered w-full" required />
                    </div>
                    <button type="submit" class="btn btn-warning w-full">Buat Voucher</button>
                </form>
            </div>
        </div>

        {{-- Promo form --}}
        <div class="card bg-base-100 border border-base-200 shadow">
            <div class="card-body">
                <h2 class="card-title text-lg">Buat Promo</h2>
                <p class="text-xs text-base-content/50">Promo tidak punya batas pemakaian, hanya expiry date.</p>
                <form action="{{ route('admin.promos.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="text" name="code" placeholder="Kode (contoh: SEAPEDIA18)" class="input input-bordered w-full" required maxlength="50" />
                    <div class="grid grid-cols-2 gap-2">
                        <select name="discount_type" class="select select-bordered w-full">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                        <input type="number" name="discount_value" placeholder="Nilai" class="input input-bordered w-full" required min="1" />
                    </div>
                    <input type="datetime-local" name="expires_at" class="input input-bordered w-full" required />
                    <button type="submit" class="btn btn-warning w-full">Buat Promo</button>
                </form>
            </div>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-3">Daftar Voucher</h2>
    <div class="overflow-x-auto mb-8">
        <table class="table bg-base-100 shadow rounded-xl">
            <thead><tr><th>Kode</th><th>Tipe</th><th>Nilai</th><th>Pemakaian</th><th>Expired</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($vouchers as $v)
                <tr>
                    <td class="font-mono font-semibold">{{ $v->code }}</td>
                    <td>{{ $v->discount_type === 'percentage' ? 'Persentase' : 'Nominal' }}</td>
                    <td>{{ $v->discount_type === 'percentage' ? $v->discount_value.'%' : 'Rp '.number_format($v->discount_value,0,',','.') }}</td>
                    <td>{{ $v->used_count }} / {{ $v->max_usage }}</td>
                    <td>{{ $v->expires_at->format('d M Y H:i') }}</td>
                    <td><span class="badge {{ $v->isValid() ? 'badge-success' : 'badge-error' }}">{{ $v->isValid() ? 'Aktif' : 'Expired/Habis' }}</span></td>
                    <td>
                        <form action="{{ route('admin.vouchers.destroy', $v) }}" method="POST" onsubmit="return confirm('Hapus voucher?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-ghost btn-xs text-error">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-base-content/50">Belum ada voucher.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h2 class="text-xl font-bold mb-3">Daftar Promo</h2>
    <div class="overflow-x-auto">
        <table class="table bg-base-100 shadow rounded-xl">
            <thead><tr><th>Kode</th><th>Tipe</th><th>Nilai</th><th>Expired</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($promos as $p)
                <tr>
                    <td class="font-mono font-semibold">{{ $p->code }}</td>
                    <td>{{ $p->discount_type === 'percentage' ? 'Persentase' : 'Nominal' }}</td>
                    <td>{{ $p->discount_type === 'percentage' ? $p->discount_value.'%' : 'Rp '.number_format($p->discount_value,0,',','.') }}</td>
                    <td>{{ $p->expires_at->format('d M Y H:i') }}</td>
                    <td><span class="badge {{ $p->isValid() ? 'badge-success' : 'badge-error' }}">{{ $p->isValid() ? 'Aktif' : 'Expired' }}</span></td>
                    <td>
                        <form action="{{ route('admin.promos.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus promo?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-ghost btn-xs text-error">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-base-content/50">Belum ada promo.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
