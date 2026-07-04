@extends('layouts.app')
@section('title', 'Dashboard Seller')
@section('content')
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="avatar placeholder">
                    <div class="bg-blue-600 text-white rounded-full w-14">
                        <span class="text-xl font-bold">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</span>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold font-heading text-gray-900">Halo, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Pusat kendali tokomu ada di sini.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 bg-gray-50 p-2 rounded-xl border border-gray-100">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-xs font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    SELLER
                </span>
                <a href="{{ route('role.select') }}" class="text-xs text-gray-400 hover:text-blue-600 transition-colors duration-200 px-2">Ganti Role</a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    @if($store)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            {{-- Store Profile Card --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-medium text-gray-500">Informasi Toko</h2>
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                </div>
                <p class="text-xl font-extrabold text-gray-900 mb-1 line-clamp-1" title="{{ $store->name }}">{{ $store->name }}</p>
                <p class="text-xs text-gray-400 mb-4 flex items-center gap-1"><span class="text-green-500">●</span> Toko Aktif</p>
                <a href="{{ route('seller.store.create') }}" class="block w-full text-center py-2 border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200">Edit Profil Toko</a>
            </div>

            {{-- Products Card --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-medium text-gray-500">Produk Aktif</h2>
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-gray-900 mb-1">{{ $products }}</p>
                <p class="text-sm text-gray-400 mb-4">item</p>
                <a href="{{ route('seller.products.index') }}" class="block w-full text-center py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors duration-200">Kelola Etalase</a>
            </div>

            {{-- Revenue Card --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-medium text-gray-500">Pendapatan Bersih</h2>
                    <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-green-600 mb-1">Rp {{ number_format($income,0,',','.') }}</p>
                <p class="text-xs text-gray-400 mb-4">Dari total {{ $orders }} pesanan selesai</p>
                <button class="w-full py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors duration-200">Cairkan Dana</button>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h2 class="text-lg font-bold mb-4 font-heading flex items-center gap-2 text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                Pintasan Penjual
            </h2>
            <div class="flex gap-4 flex-wrap">
                <a href="{{ route('seller.products.create') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-full transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Tambah Produk
                </a>
                <a href="{{ route('seller.orders') }}" class="inline-flex items-center gap-2 px-6 py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-full hover:bg-gray-50 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    Pesanan Masuk
                </a>
                <a href="{{ route('seller.products.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-full hover:bg-gray-50 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    Semua Produk
                </a>
            </div>
        </div>
    @else
        <div class="flex items-center justify-center py-16">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm max-w-lg w-full overflow-hidden">
                <div class="h-1.5 bg-blue-600"></div>
                <div class="p-10 text-center">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl">🏪</span>
                    </div>
                    <h2 class="text-2xl font-extrabold font-heading mb-2 text-gray-900">Buka Toko Pertamamu</h2>
                    <p class="text-gray-500 mb-8 leading-relaxed">Jangkau jutaan pelanggan potensial di seluruh Indonesia. Proses pendaftaran cepat dan gratis!</p>
                    <a href="{{ route('seller.store.create') }}" class="block w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-bold rounded-full transition-colors duration-200">Buka Toko Sekarang</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
