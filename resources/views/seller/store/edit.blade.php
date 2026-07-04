@extends('layouts.app')
@section('title', 'Edit Toko')
@section('content')
<div class="max-w-xl mx-auto px-4 py-8">
    <div class="card bg-base-100 shadow border border-base-200">
        <div class="card-body">
            <h1 class="card-title text-xl mb-4">🏪 Edit Toko</h1>
            @if($errors->any())<div class="alert alert-error text-sm mb-4">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
            <form action="{{ route('seller.store.update') }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Nama Toko</span></label>
                    <input type="text" name="name" value="{{ old('name', $store->name) }}" class="input input-bordered w-full" required maxlength="100" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Deskripsi</span></label>
                    <textarea name="description" rows="3" class="textarea textarea-bordered w-full">{{ old('description', $store->description) }}</textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-warning flex-1">Simpan Perubahan</button>
                    <a href="{{ route('seller.dashboard') }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
