@extends('layouts.app')
@section('title', 'Ulasan Aplikasi')
@section('content')
{{-- Hero Section --}}
<div class="bg-white border-b border-gray-100 py-14">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-50 rounded-2xl mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-[#2563eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
        </div>
        <h1 class="text-3xl md:text-4xl font-extrabold font-heading text-gray-900 mb-3">Suara Pengguna <span class="text-[#2563eb]">SEAPEDIA</span></h1>
        <p class="text-gray-500 text-lg max-w-2xl mx-auto leading-relaxed">Bagikan pengalamanmu menggunakan SEAPEDIA. Ulasanmu sangat berarti untuk pengembangan layanan kami ke depan.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        {{-- Form Section --}}
        <div class="lg:col-span-5">
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm sticky top-24">
                <div class="p-7">
                    {{-- Form Header --}}
                    <div class="mb-6">
                        <h2 class="text-xl font-bold font-heading text-gray-900 mb-1.5 flex items-center gap-2.5">
                            <span class="inline-flex items-center justify-center w-9 h-9 bg-blue-50 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#2563eb]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </span>
                            Tulis Ulasanmu
                        </h2>
                        <p class="text-sm text-gray-500 ml-[46px]">Tidak perlu membeli barang untuk memberikan ulasan aplikasi secara keseluruhan.</p>
                    </div>

                    {{-- Errors --}}
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-6">
                            <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-5">
                        @csrf
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Kamu</label>
                            <input type="text" name="reviewer_name" value="{{ old('reviewer_name', Auth::check() ? Auth::user()->name : '') }}"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 transition-all duration-200"
                                placeholder="John Doe" required maxlength="100" />
                        </div>

                        {{-- Rating --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Rating Pengalamanmu</label>
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex justify-center">
                                <div class="rating rating-lg">
                                    @for($i=1;$i<=5;$i++)
                                    <input type="radio" name="rating" value="{{ $i }}" class="mask mask-star-2 bg-amber-400 transition-transform hover:scale-110" {{ $i==5?'checked':'' }} />
                                    @endfor
                                </div>
                            </div>
                        </div>

                        {{-- Comment --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Komentar & Saran</label>
                            <textarea name="comment" rows="4"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-[#2563eb] focus:ring-2 focus:ring-[#2563eb]/20 transition-all duration-200 leading-relaxed resize-none"
                                placeholder="Ceritakan hal apa yang paling kamu suka dari SEAPEDIA..." required maxlength="1000">{{ old('comment') }}</textarea>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full bg-[#2563eb] hover:bg-[#1d4ed8] text-white font-semibold text-sm py-3 rounded-xl shadow-sm hover:shadow-md transition-all duration-200">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Reviews List --}}
        <div class="lg:col-span-7">
            {{-- List Header --}}
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                <h2 class="text-xl font-bold font-heading text-gray-900 flex items-center gap-2.5">
                    <span class="bg-blue-50 text-[#2563eb] font-bold text-sm w-9 h-9 flex items-center justify-center rounded-xl">{{ $reviews->count() }}</span>
                    Ulasan Pengguna
                </h2>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span>Urutkan:</span>
                    <select class="bg-white border border-gray-200 rounded-lg text-sm py-1.5 px-3 focus:outline-none focus:border-[#2563eb] focus:ring-1 focus:ring-[#2563eb]/20">
                        <option>Terbaru</option>
                        <option>Rating Tertinggi</option>
                    </select>
                </div>
            </div>

            @if($reviews->isEmpty())
                {{-- Empty State --}}
                <div class="text-center py-20 bg-white border border-gray-100 border-dashed rounded-2xl">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl mx-auto flex items-center justify-center text-3xl mb-4">💬</div>
                    <h3 class="text-lg font-bold font-heading text-gray-900 mb-1.5">Jadilah yang Pertama!</h3>
                    <p class="text-sm text-gray-500">Belum ada ulasan yang diberikan. Beritahu kami pendapatmu.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($reviews as $review)
                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200">
                        <div class="p-6">
                            {{-- Review Header --}}
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#2563eb] text-white rounded-xl flex items-center justify-center font-bold text-sm shadow-sm">
                                        {{ strtoupper(substr($review->reviewer_name,0,1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ e($review->reviewer_name) }}</p>
                                        <p class="text-xs text-gray-400 flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Pengguna Terverifikasi
                                        </p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400 bg-gray-50 px-2.5 py-1 rounded-lg">{{ $review->created_at->diffForHumans() }}</span>
                            </div>

                            {{-- Stars --}}
                            <div class="flex text-amber-400 mb-3">
                                @for($i=1;$i<=5;$i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $i<=$review->rating?'fill-current':'text-gray-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                @endfor
                            </div>

                            {{-- Comment --}}
                            <p class="text-sm text-gray-600 leading-relaxed">"{{ e($review->comment) }}"</p>

                            {{-- Footer --}}
                            <div class="mt-4 pt-3.5 border-t border-gray-100 flex gap-4">
                                <button class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-[#2563eb] transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                    </svg>
                                    Membantu
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
