@extends('layouts.app')
@section('title', $product->name)
@section('content')
{{-- Breadcrumb --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <div class="text-sm breadcrumbs text-gray-400">
            <ul>
                <li><a href="{{ route('home') }}" class="hover:text-primary">Beranda</a></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-primary">Produk</a></li>
                <li class="text-neutral font-medium">{{ Str::limit($product->name, 30) }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="bg-base-200/30">
    <div class="max-w-7xl mx-auto px-4 py-8 md:py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Product Image --}}
            <div class="lg:col-span-5">
                <div class="sticky top-20">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
                        <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden flex items-center justify-center">
                            <img src="{{ $product->image_url ?? 'https://placehold.co/600x600?text=SEAPEDIA' }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover rounded-xl hover:scale-105 transition-transform duration-300" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product Info --}}
            <div class="lg:col-span-4 space-y-5">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h1 class="text-xl md:text-2xl font-extrabold text-neutral font-heading mb-3 leading-tight">{{ $product->name }}</h1>

                    <div class="flex items-center gap-3 text-sm text-gray-400 mb-4">
                        <div class="flex items-center text-amber-400 gap-0.5">★★★★★</div>
                        <span>(Belum ada ulasan)</span>
                    </div>

                    <p class="text-3xl font-extrabold text-primary mb-4">Rp {{ number_format($product->price,0,',','.') }}</p>

                    @if($product->stock > 0)
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Tersedia ({{ $product->stock }} stok)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-red-600 bg-red-50 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>Stok Habis
                        </span>
                    @endif
                </div>

                {{-- Description --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-semibold text-neutral mb-3 text-sm">Deskripsi Produk</h3>
                    <div class="text-sm text-gray-500 leading-relaxed">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                {{-- Store Info --}}
                @if($product->store)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="avatar placeholder">
                                <div class="bg-primary/10 text-primary rounded-full w-11 text-lg font-bold">
                                    <span>{{ strtoupper(substr($product->store->name,0,1)) }}</span>
                                </div>
                            </div>
                            <div>
                                <p class="font-semibold text-neutral text-sm">{{ $product->store->name }}</p>
                                <p class="text-xs text-gray-400 flex items-center gap-1"><span class="text-emerald-500">●</span> Online</p>
                            </div>
                        </div>
                        <a href="{{ route('stores.show', $product->store) }}" class="btn btn-outline btn-primary btn-xs rounded-full px-4 font-normal">Kunjungi</a>
                    </div>
                </div>
                @endif
            </div>

            {{-- Action Card --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sticky top-20">
                    <h3 class="font-semibold text-neutral mb-4">Atur Pembelian</h3>

                    @auth
                        @if(session('active_role') === 'buyer')
                            @if($product->stock > 0)
                            <form action="{{ route('buyer.cart.add') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}" />

                                <div class="flex items-center gap-3">
                                    <div class="join border border-gray-200 rounded-lg">
                                        <button type="button" class="btn btn-sm join-item bg-white border-none hover:bg-gray-50" onclick="document.getElementById('qty').stepDown()">−</button>
                                        <input type="number" id="qty" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="input input-sm join-item w-14 text-center font-bold bg-white border-none focus:outline-none" />
                                        <button type="button" class="btn btn-sm join-item bg-white border-none hover:bg-gray-50" onclick="document.getElementById('qty').stepUp()">+</button>
                                    </div>
                                    <span class="text-xs text-gray-400">Stok: {{ $product->stock }}</span>
                                </div>

                                <div class="flex items-center justify-between py-3 border-y border-gray-100">
                                    <span class="text-sm text-gray-500">Subtotal</span>
                                    <span class="font-bold text-primary" id="subtotal">Rp {{ number_format($product->price,0,',','.') }}</span>
                                </div>

                                <button type="submit" class="btn btn-primary w-full rounded-full text-white font-semibold shadow-sm shadow-primary/20">+ Keranjang</button>
                                <button type="button" class="btn btn-outline btn-primary w-full rounded-full font-normal">Beli Langsung</button>
                            </form>
                            @else
                                <p class="text-sm text-gray-400 text-center py-4">Stok sedang kosong.</p>
                                <button class="btn btn-disabled w-full rounded-full">Tidak Tersedia</button>
                            @endif
                        @else
                            <div class="bg-blue-50 text-primary/80 p-3 rounded-xl text-sm mb-4">
                                Aktifkan role <strong>Buyer</strong> untuk berbelanja.
                            </div>
                            <a href="{{ route('role.select') }}" class="btn btn-primary w-full rounded-full text-white font-semibold">Ganti Role</a>
                        @endif
                    @else
                        <p class="text-sm text-gray-400 text-center mb-4">Masuk ke akunmu untuk mulai berbelanja.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary w-full rounded-full text-white font-semibold mb-2">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm text-primary font-medium text-center block hover:underline">Daftar Akun Baru</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const price = {{ $product->price }};
    const qtyInput = document.getElementById('qty');
    const subtotalEl = document.getElementById('subtotal');

    if(qtyInput && subtotalEl) {
        qtyInput.addEventListener('input', function() {
            let val = parseInt(this.value) || 1;
            let max = parseInt(this.getAttribute('max'));
            let min = parseInt(this.getAttribute('min'));
            if(val > max) { this.value = max; val = max; }
            if(val < min) { this.value = min; val = min; }
            subtotalEl.innerText = 'Rp ' + (val * price).toLocaleString('id-ID');
        });
    }
</script>
@endsection
