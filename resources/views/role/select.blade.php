@extends('layouts.app')
@section('title', 'Pilih Role')
@section('content')
<div class="min-h-[85vh] flex items-center justify-center bg-gray-50/50 px-4 py-12">
    <div class="w-full max-w-2xl text-center">
        {{-- Header --}}
        <div class="mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-2xl mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#2563eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold font-heading text-gray-900 mb-3">Kamu Mau Jadi Apa Hari Ini?</h2>
            <p class="text-gray-500 text-base max-w-lg mx-auto leading-relaxed">Akunmu memiliki lebih dari satu peran. Pilih peran untuk sesi ini agar kami bisa menyesuaikan tampilan dan fitur untukmu.</p>
        </div>

        {{-- Error --}}
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-8 max-w-md mx-auto">{{ $errors->first() }}</div>
        @endif

        {{-- Role Cards Grid --}}
        <form action="{{ route('role.set') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @csrf
            @foreach($roles as $role)
            @php
                $roleData = [
                    'buyer'  => ['icon'=>'🛒', 'label'=>'Pembeli', 'desc'=>'Belanja produk, kelola keranjang & alamat'],
                    'seller' => ['icon'=>'🏪', 'label'=>'Penjual', 'desc'=>'Kelola toko, inventaris, dan produk'],
                    'driver' => ['icon'=>'🚚', 'label'=>'Driver', 'desc'=>'Ambil lowongan dan antar pesanan'],
                    'admin'  => ['icon'=>'👑', 'label'=>'Admin', 'desc'=>'Monitor dan kelola seluruh sistem']
                ];
                $data = $roleData[$role] ?? ['icon'=>'👤', 'label'=>$role, 'desc'=>''];
            @endphp
            <button type="submit" name="role" value="{{ $role }}"
                class="group bg-white border border-gray-100 rounded-2xl p-6 text-left shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#2563eb]/30">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl mb-4 group-hover:bg-[#2563eb]/10 transition-colors duration-200">
                    {{ $data['icon'] }}
                </div>
                <h3 class="font-bold font-heading text-lg text-gray-900 mb-1 capitalize group-hover:text-[#2563eb] transition-colors duration-200">{{ $data['label'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $data['desc'] }}</p>
                <div class="mt-4 flex items-center text-xs font-medium text-[#2563eb] opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    Lanjutkan
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </button>
            @endforeach
        </form>

        {{-- Logout --}}
        <div class="mt-10">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-red-500 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
