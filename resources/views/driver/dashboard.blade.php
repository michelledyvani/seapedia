@extends('layouts.app')
@section('title', 'Dashboard Driver')
@section('content')
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="avatar placeholder">
                    <div class="bg-blue-600 text-white rounded-full w-14">
                        <span class="text-xl font-bold">{{ strtoupper(substr($driver->name,0,1)) }}</span>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold font-heading text-gray-900">Halo, {{ $driver->name }}! 👋</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Siap untuk mengantar pesanan hari ini?</p>
                </div>
            </div>
            <div class="flex items-center gap-2 bg-gray-50 p-2 rounded-xl border border-gray-100">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-green-50 text-green-600 text-xs font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    DRIVER
                </span>
                <a href="{{ route('role.select') }}" class="text-xs text-gray-400 hover:text-blue-600 transition-colors duration-200 px-2">Ganti Role</a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        {{-- Revenue Card --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-medium text-gray-500">Total Pendapatan</h2>
                <div class="p-2 bg-green-50 text-green-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-green-600 mb-1">Rp {{ number_format($totalEarned,0,',','.') }}</p>
            <p class="text-xs text-gray-400">Dari semua pengiriman selesai</p>
        </div>

        {{-- Completed Jobs Card --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-medium text-gray-500">Pesanan Selesai</h2>
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-gray-900 mb-1">{{ $history->total() }}</p>
            <p class="text-xs text-gray-400">Telah berhasil diantar</p>
        </div>

        {{-- Status Card --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex flex-col items-center justify-center text-center h-full">
                <div class="relative w-14 h-14 mb-3">
                    @if($activeJob)
                    <div class="absolute inset-0 bg-amber-100 rounded-full animate-ping"></div>
                    @endif
                    <div class="w-14 h-14 {{ $activeJob ? 'bg-amber-50 text-amber-600' : 'bg-gray-100 text-gray-400' }} rounded-full flex items-center justify-center text-2xl relative z-10">🚚</div>
                </div>
                <h2 class="text-sm font-medium text-gray-500 mb-2">Status Saat Ini</h2>
                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold {{ $activeJob ? 'bg-amber-50 text-amber-600' : 'bg-gray-100 text-gray-500' }}">{{ $activeJob ? 'SEDANG MENGANTAR' : 'STANDBY' }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Active Job Section --}}
        <div class="lg:col-span-1">
            <h2 class="text-xl font-bold mb-4 font-heading flex items-center gap-2 text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                Tugas Aktif
            </h2>

            @if($activeJob)
            <div class="bg-white rounded-xl border border-amber-200 shadow-sm overflow-hidden">
                <div class="h-1 bg-amber-400"></div>
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <span class="inline-flex px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 text-xs font-bold">ORDER #{{ $activeJob->id }}</span>
                        <span class="text-xs text-gray-400 flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span> Berlangsung</span>
                    </div>

                    <div class="space-y-4">
                        {{-- Pickup --}}
                        <div class="flex gap-3 relative">
                            <div class="w-6 flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs">🏪</div>
                                <div class="w-0.5 h-full bg-gray-200 mt-1"></div>
                            </div>
                            <div class="pb-2">
                                <p class="text-xs text-gray-400 uppercase font-semibold">Ambil di</p>
                                <p class="font-bold text-sm text-gray-900">{{ $activeJob->store->name }}</p>
                            </div>
                        </div>

                        {{-- Dropoff --}}
                        <div class="flex gap-3">
                            <div class="w-6 flex flex-col items-center">
                                <div class="w-6 h-6 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-xs">📍</div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-semibold">Antar ke</p>
                                <p class="font-bold text-sm text-gray-900">{{ $activeJob->buyer->name }}</p>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">{{ $activeJob->address->full_address }}, {{ $activeJob->address->city }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-3 rounded-lg mt-4 mb-2 flex justify-between items-center border border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Ongkos Kirim</span>
                        <span class="font-extrabold text-green-600 text-lg">Rp {{ number_format($activeJob->delivery_fee,0,',','.') }}</span>
                    </div>

                    <form action="{{ route('driver.jobs.complete', $activeJob) }}" method="POST" class="mt-3">
                        @csrf
                        <button class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Selesaikan Pesanan
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm text-center p-8">
                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">💤</div>
                <h3 class="font-bold text-gray-900 mb-2">Tidak Ada Pengiriman</h3>
                <p class="text-sm text-gray-500 mb-6">Kamu sedang bebas tugas. Temukan pesanan baru untuk diantar sekarang.</p>
                <a href="{{ route('driver.jobs') }}" class="block w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-full transition-colors duration-200">Cari Lowongan</a>
            </div>
            @endif
        </div>

        {{-- History Section --}}
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold font-heading text-gray-900">Riwayat Pengiriman</h2>
                <a href="#" class="text-sm text-gray-400 hover:text-blue-600 transition-colors duration-200">Lihat Semua</a>
            </div>

            @if($history->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center text-gray-400">
                    Belum ada riwayat pengiriman.
                </div>
            @else
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-100 bg-gray-50">
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 tracking-wider">Order ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 tracking-wider">Rute</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 tracking-wider">Tanggal</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 tracking-wider">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($history as $h)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-3 font-mono text-sm font-semibold text-gray-900">#{{ $h->id }}</td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $h->store->name }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">ke {{ $h->buyer->name }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $h->updated_at->format('d M Y, H:i') }}</td>
                                    <td class="px-4 py-3 text-right font-extrabold text-green-600">Rp {{ number_format($h->delivery_fee,0,',','.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 flex justify-center">{{ $history->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
