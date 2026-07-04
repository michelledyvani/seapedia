@extends('layouts.app')
@section('title', 'Kelola Produk')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div><h1 class="text-2xl font-bold">Produk Toko: {{ $store->name }}</h1><p class="text-sm text-base-content/60">{{ $products->total() }} produk</p></div>
        <a href="{{ route('seller.products.create') }}" class="btn btn-warning">+ Tambah Produk</a>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-16"><p class="text-4xl mb-3">📦</p><p class="font-semibold">Belum ada produk</p><a href="{{ route('seller.products.create') }}" class="btn btn-warning btn-sm mt-3">Tambah Produk Pertama</a></div>
    @else
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full bg-base-100 shadow rounded-xl">
                <thead><tr><th>Produk</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->image_url ?? 'https://placehold.co/50x50' }}" class="w-12 h-12 rounded object-cover" />
                                <div><p class="font-semibold text-sm">{{ $product->name }}</p><p class="text-xs text-base-content/60 line-clamp-1">{{ Str::limit($product->description,50) }}</p></div>
                            </div>
                        </td>
                        <td class="font-semibold text-warning">Rp {{ number_format($product->price,0,',','.') }}</td>
                        <td><span class="badge {{ $product->stock>0?'badge-success':'badge-error' }}">{{ $product->stock }}</span></td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('seller.products.edit', $product) }}" class="btn btn-sm btn-outline">Edit</a>
                                <form action="{{ route('seller.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-error btn-outline">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $products->links() }}</div>
    @endif
</div>
@endsection
