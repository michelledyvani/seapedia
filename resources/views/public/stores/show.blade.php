@extends('layouts.app')
@section('title', $store->name)
@section('content')
{{-- Store Header --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row items-center md:items-end gap-6 text-center md:text-left">
            <div class="avatar placeholder">
                <div class="bg-primary/10 text-primary rounded-2xl w-20 h-20 text-3xl font-extrabold shadow-sm">
                    <span>{{ strtoupper(substr($store->name,0,1)) }}</span>
                </div>
            </div>
            <div class="flex-1">
                <div class="flex flex-col md:flex-row md:items-center gap-2 mb-1">
                    <h1 class="text-2xl font-extrabold text-neutral font-heading">{{ $store->name }}</h1>
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Online</span>
                </div>
                <p class="text-sm text-gray-500">{{ $store->description ?: 'Toko terpercaya di SEAPEDIA.' }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $products->total() }} produk tersedia</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-base-200/30 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h2 class="text-lg font-bold text-neutral mb-5 font-heading">Etalase Produk</h2>

        @if($products->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                <div class="w-16 h-16 bg-gray-50 rounded-full mx-auto flex items-center justify-center text-3xl mb-4">📦</div>
                <h3 class="text-lg font-bold text-neutral mb-1">Toko Masih Kosong</h3>
                <p class="text-gray-400 text-sm">Penjual belum menambahkan produk.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @foreach($products as $product)
                <a href="{{ route('products.show', $product) }}" class="card-clean group overflow-hidden">
                    <figure class="aspect-square bg-gray-50 p-3">
                        <img src="{{ $product->image_url ?? 'https://placehold.co/300x300?text=SEAPEDIA' }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition-transform duration-300"
                             loading="lazy" />
                    </figure>
                    <div class="p-4 pt-3">
                        <h3 class="text-sm font-semibold text-neutral line-clamp-2 leading-snug mb-2 group-hover:text-primary transition-colors">{{ $product->name }}</h3>
                        <div class="flex items-center justify-between">
                            <span class="text-primary font-bold">Rp {{ number_format($product->price,0,',','.') }}</span>
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                <div class="w-4 h-4 bg-primary rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-10 flex justify-center">{{ $products->links() }}</div>
        @endif
    </div>
</div>
@endsection
