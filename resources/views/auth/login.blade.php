@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="min-h-[85vh] flex items-center justify-center px-4 py-12" style="background-color: #f8fafc;">
    <div class="w-full max-w-md">
        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-8 sm:p-10">
            {{-- Header --}}
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold font-heading text-gray-900 mb-2">Selamat Datang Kembali! 👋</h2>
                <p class="text-gray-500 text-sm">Masuk ke akunmu untuk melanjutkan berbelanja atau berjualan.</p>
            </div>

            {{-- Errors --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-4 mb-6">
                    <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 transition-all duration-200" placeholder="contoh@email.com" required autofocus />
                </div>
                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 transition-all duration-200" placeholder="••••••••" required />
                </div>
                {{-- Remember & Forgot --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-[#2563eb] focus:ring-[#2563eb]" />
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm font-semibold text-[#2563eb] hover:text-blue-700 transition-colors">Lupa Password?</a>
                </div>
                {{-- Submit --}}
                <button type="submit" class="w-full py-2.5 rounded-xl text-white font-bold text-base transition-all duration-200 hover:shadow-md" style="background-color: #2563eb;">Masuk Sekarang</button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400">Atau gunakan demo akun</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Demo Buttons --}}
            <div class="grid grid-cols-2 gap-2 text-xs">
                <button onclick="fillLogin('admin@seapedia.id', 'password')" class="py-2 px-3 rounded-lg border border-gray-200 bg-white text-gray-600 font-medium hover:border-[#2563eb] hover:text-[#2563eb] hover:bg-blue-50 transition-all duration-200">👑 Admin</button>
                <button onclick="fillLogin('seller@seapedia.id', 'password')" class="py-2 px-3 rounded-lg border border-gray-200 bg-white text-gray-600 font-medium hover:border-[#2563eb] hover:text-[#2563eb] hover:bg-blue-50 transition-all duration-200">🏪 Seller</button>
                <button onclick="fillLogin('buyer@seapedia.id', 'password')" class="py-2 px-3 rounded-lg border border-gray-200 bg-white text-gray-600 font-medium hover:border-[#2563eb] hover:text-[#2563eb] hover:bg-blue-50 transition-all duration-200">🛒 Buyer</button>
                <button onclick="fillLogin('driver@seapedia.id', 'password')" class="py-2 px-3 rounded-lg border border-gray-200 bg-white text-gray-600 font-medium hover:border-[#2563eb] hover:text-[#2563eb] hover:bg-blue-50 transition-all duration-200">🚚 Driver</button>
            </div>

            {{-- Register Link --}}
            <p class="text-center text-sm text-gray-500 mt-8">
                Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-[#2563eb] hover:text-blue-700 transition-colors">Daftar sekarang</a>
            </p>
        </div>
    </div>
</div>

<script>
function fillLogin(email, password) {
    document.querySelector('input[name="email"]').value = email;
    document.querySelector('input[name="password"]').value = password;
}
</script>
@endsection
