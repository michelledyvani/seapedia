@extends('layouts.app')
@section('title', 'Daftar Akun')
@section('content')
<div class="min-h-[85vh] flex items-center justify-center px-4 py-12" style="background-color: #f8fafc;">
    <div class="w-full max-w-lg">
        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-8 sm:p-10">
            {{-- Header --}}
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold font-heading text-gray-900 mb-2">Buat Akun Baru ✨</h2>
                <p class="text-gray-500 text-sm">Lengkapi data di bawah untuk bergabung.</p>
            </div>

            {{-- Errors --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-4 mb-6">
                    <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                {{-- Name & Username --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 transition-all duration-200" placeholder="John Doe" required autofocus />
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-1.5">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 transition-all duration-200" placeholder="johndoe123" required />
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 transition-all duration-200" placeholder="contoh@email.com" required />
                </div>

                {{-- Password & Confirm --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <input type="password" id="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 transition-all duration-200" placeholder="Min. 8 karakter" required />
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 transition-all duration-200" placeholder="Ulangi password" required />
                    </div>
                </div>

                {{-- Terms Checkbox --}}
                <label class="flex items-start gap-3 cursor-pointer mt-4">
                    <input type="checkbox" required class="w-4 h-4 mt-0.5 rounded border-gray-300 text-[#2563eb] focus:ring-[#2563eb]" />
                    <span class="text-sm text-gray-500 leading-relaxed">Saya setuju dengan <a href="#" class="text-[#2563eb] font-semibold hover:text-blue-700 transition-colors">Syarat &amp; Ketentuan</a> serta <a href="#" class="text-[#2563eb] font-semibold hover:text-blue-700 transition-colors">Kebijakan Privasi</a> SEAPEDIA.</span>
                </label>

                {{-- Submit --}}
                <button type="submit" class="w-full py-2.5 rounded-xl text-white font-bold text-base transition-all duration-200 hover:shadow-md mt-6" style="background-color: #2563eb;">Daftar Sekarang</button>
            </form>

            {{-- Login Link --}}
            <p class="text-center text-sm text-gray-500 mt-8">
                Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-[#2563eb] hover:text-blue-700 transition-colors">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
