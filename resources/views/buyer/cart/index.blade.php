@extends('layouts.app')
@section('title', 'Keranjang Belanja')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-2">🛒 Keranjang Belanjaku</h1>

    <div class="alert alert-info text-sm mb-4">
        <span><strong>Aturan SEAPEDIA:</strong> Keranjang hanya bisa berisi produk dari <strong>satu toko</strong>. Checkout ke toko berbeda harus dilakukan terpisah.</span>
    </div>

    @if($items->isEmpty())
        <div class="text-center py-16">
            <p class="text-5xl mb-4">🛒</p>
            <p class="text-lg font-semibold">Keranjangmu masih kosong</p>
            <a href="{{ route('products.index') }}" class="btn btn-warning mt-4">Mulai Belanja</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-3">
                <div class="flex items-center gap-2 mb-2"><span>🏪</span><span class="font-semibold">{{ $cart->store->name ?? 'Toko' }}</span></div>
                @foreach($items as $item)
                <div class="card bg-base-100 border border-base-200 shadow-sm">
                    <div class="card-body p-4 flex flex-row gap-4 items-center">
                        <img src="{{ $item->product->image_url ?? 'https://placehold.co/80x80' }}" class="w-20 h-20 rounded object-cover shrink-0" />
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm truncate">{{ $item->product->name }}</p>
                            <p class="text-warning font-bold">Rp {{ number_format($item->product->price,0,',','.') }}</p>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <form action="{{ route('buyer.cart.update', $item) }}" method="POST" class="flex items-center gap-1">
                                @csrf @method('PUT')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                       class="input input-bordered input-sm w-16 text-center" onchange="this.form.submit()" />
                            </form>
                            <p class="text-xs text-base-content/50">Rp {{ number_format($item->product->price * $item->quantity,0,',','.') }}</p>
                            <form action="{{ route('buyer.cart.remove', $item) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-ghost btn-xs text-error">🗑 Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
                <form action="{{ route('buyer.cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline btn-error btn-sm">Kosongkan Keranjang</button>
                </form>
            </div>

            <div>
                <div class="card bg-base-100 border border-base-200 shadow sticky top-20">
                    <div class="card-body">
                        <h2 class="card-title text-base">Ringkasan Belanja</h2>
                        @php $subtotal = $items->sum(fn($i) => $i->product->price * $i->quantity); @endphp
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span>Subtotal ({{ $items->count() }} item)</span><span>Rp {{ number_format($subtotal,0,',','.') }}</span></div>
                            <div class="flex justify-between text-base-content/60"><span>Ongkos kirim</span><span>Ditentukan saat checkout</span></div>
                            <div class="flex justify-between text-base-content/60"><span>PPN 12%</span><span>Rp {{ number_format($subtotal*0.12,0,',','.') }}</span></div>
                            <div class="divider my-1"></div>
                            <div class="flex justify-between font-bold text-base"><span>Estimasi Total</span><span class="text-warning">Rp {{ number_format($subtotal+($subtotal*0.12),0,',','.') }}+</span></div>
                        </div>
                        <a href="{{ route('buyer.checkout') }}" class="btn btn-warning w-full mt-3">Lanjut ke Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
