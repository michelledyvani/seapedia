@extends('layouts.app')
@section('title', 'Alamat Pengiriman')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">📍 Alamat Pengiriman</h1>

    <div class="card bg-base-100 border border-base-200 shadow mb-6">
        <div class="card-body">
            <h2 class="card-title text-lg">Tambah Alamat Baru</h2>
            @if($errors->any())<div class="alert alert-error text-sm"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
            <form action="{{ route('buyer.addresses.store') }}" method="POST" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" name="label" placeholder="Label (Rumah/Kantor)" class="input input-bordered" required maxlength="50" />
                    <input type="text" name="recipient_name" placeholder="Nama Penerima" class="input input-bordered" required maxlength="100" />
                </div>
                <input type="text" name="phone" placeholder="Nomor Telepon" class="input input-bordered w-full" required maxlength="20" />
                <textarea name="full_address" placeholder="Alamat Lengkap" class="textarea textarea-bordered w-full" required maxlength="500"></textarea>
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" name="city" placeholder="Kota" class="input input-bordered" required maxlength="100" />
                    <input type="text" name="province" placeholder="Provinsi" class="input input-bordered" required maxlength="100" />
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_default" value="1" class="checkbox checkbox-warning checkbox-sm" />
                    <span class="text-sm">Jadikan alamat utama</span>
                </label>
                <button type="submit" class="btn btn-warning w-full">Simpan Alamat</button>
            </form>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-3">Alamat Tersimpan</h2>
    @if($addresses->isEmpty())
        <p class="text-base-content/50 text-sm">Belum ada alamat tersimpan.</p>
    @else
        <div class="space-y-3">
            @foreach($addresses as $addr)
            <div class="card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body py-3 flex-row justify-between items-start">
                    <div>
                        <p class="font-semibold">{{ $addr->label }} @if($addr->is_default)<span class="badge badge-warning badge-xs ml-1">Default</span>@endif</p>
                        <p class="text-sm text-base-content/70">{{ $addr->recipient_name }} · {{ $addr->phone }}</p>
                        <p class="text-sm text-base-content/70">{{ $addr->full_address }}, {{ $addr->city }}, {{ $addr->province }}</p>
                    </div>
                    <form action="{{ route('buyer.addresses.destroy', $addr) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-ghost btn-xs text-error">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
