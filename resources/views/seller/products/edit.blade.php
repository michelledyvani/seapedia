@extends('layouts.app')
@section('title', 'Edit Produk')
@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="card bg-base-100 shadow border border-base-200">
        <div class="card-body">
            <h1 class="card-title text-xl mb-4">✏️ Edit Produk</h1>
            @if($errors->any())<div class="alert alert-error text-sm mb-4">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
            <form action="{{ route('seller.products.update', $product) }}" method="POST" class="space-y-4">
                @csrf @method('PUT')
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Nama Produk *</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="input input-bordered w-full" required maxlength="200" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Deskripsi *</span></label>
                    <textarea name="description" rows="4" class="textarea textarea-bordered w-full" required>{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Harga (Rp) *</span></label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" class="input input-bordered w-full" min="0" required />
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Stok *</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="input input-bordered w-full" min="0" required />
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">URL Gambar</span></label>
                    <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}" class="input input-bordered w-full" />
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-warning flex-1">Simpan Perubahan</button>
                    <a href="{{ route('seller.products.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
