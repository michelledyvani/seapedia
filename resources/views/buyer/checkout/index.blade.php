@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">🧾 Checkout</h1>

    @if($errors->any())<div class="alert alert-error text-sm mb-4">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif

    <form action="{{ route('buyer.checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <div class="card bg-base-100 border border-base-200 shadow">
                    <div class="card-body">
                        <h2 class="card-title text-base">📍 Alamat Pengiriman</h2>
                        @if($addresses->isEmpty())
                            <div class="alert alert-warning text-sm">
                                Kamu belum punya alamat. <a href="{{ route('buyer.addresses') }}" class="btn btn-xs btn-warning ml-2">Tambah Alamat</a>
                            </div>
                        @else
                            <div class="space-y-2">
                                @foreach($addresses as $addr)
                                <label class="flex items-start gap-3 p-3 border border-base-300 rounded-lg cursor-pointer hover:border-warning">
                                    <input type="radio" name="address_id" value="{{ $addr->id }}" class="radio radio-warning mt-1" {{ $addr->is_default?'checked':'' }} required />
                                    <div>
                                        <p class="font-semibold text-sm">{{ $addr->label }} @if($addr->is_default)<span class="badge badge-warning badge-xs ml-1">Default</span>@endif</p>
                                        <p class="text-xs text-base-content/70">{{ $addr->recipient_name }} · {{ $addr->phone }}</p>
                                        <p class="text-xs text-base-content/70">{{ $addr->full_address }}, {{ $addr->city }}, {{ $addr->province }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card bg-base-100 border border-base-200 shadow">
                    <div class="card-body">
                        <h2 class="card-title text-base">🚚 Metode Pengiriman</h2>
                        <div class="space-y-2">
                            @foreach([['instant','Instant (Hari Ini)','Estimasi tiba hari ini',25000],['nextday','Next Day (Besok)','Estimasi tiba besok',15000],['regular','Regular (3-7 Hari)','Estimasi 3-7 hari kerja',9000]] as [$val,$label,$desc,$fee])
                            <label class="flex items-center gap-3 p-3 border border-base-300 rounded-lg cursor-pointer hover:border-warning delivery-option" data-fee="{{ $fee }}">
                                <input type="radio" name="delivery_method" value="{{ $val }}" class="radio radio-warning delivery-radio" {{ $val==='regular'?'checked':'' }} required />
                                <div class="flex-1"><p class="font-semibold text-sm">{{ $label }}</p><p class="text-xs text-base-content/60">{{ $desc }}</p></div>
                                <span class="font-bold text-warning text-sm">Rp {{ number_format($fee,0,',','.') }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 border border-base-200 shadow">
                    <div class="card-body">
                        <h2 class="card-title text-base">🎟 Kode Diskon</h2>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" name="voucher_code" placeholder="Kode Voucher" class="input input-bordered input-sm w-full" />
                            <input type="text" name="promo_code" placeholder="Kode Promo" class="input input-bordered input-sm w-full" />
                        </div>
                        <p class="text-xs text-base-content/50 mt-1">Voucher dan promo bisa dikombinasikan. Diskon dihitung sebelum PPN 12%.</p>
                    </div>
                </div>
            </div>

            <div>
                <div class="card bg-base-100 border border-base-200 shadow sticky top-20">
                    <div class="card-body">
                        <h2 class="card-title text-base">Ringkasan Pesanan</h2>
                        <div class="space-y-2 mb-3">
                            @foreach($cart->items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-base-content/70">{{ Str::limit($item->product->name,20) }} ×{{ $item->quantity }}</span>
                                <span>Rp {{ number_format($item->product->price * $item->quantity,0,',','.') }}</span>
                            </div>
                            @endforeach
                        </div>
                        @php
                            $subtotal = $cart->items->sum(fn($i) => $i->product->price * $i->quantity);
                            $tax = round($subtotal * 0.12);
                        @endphp
                        <div class="divider my-1"></div>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($subtotal,0,',','.') }}</span></div>
                            <div class="flex justify-between text-base-content/60"><span>Ongkos Kirim</span><span id="deliveryFeeDisplay">Rp 9.000</span></div>
                            <div class="flex justify-between text-base-content/60"><span>PPN 12%</span><span>Rp {{ number_format($tax,0,',','.') }}</span></div>
                            <div class="divider my-1"></div>
                            <div class="flex justify-between font-bold text-base"><span>Total</span><span class="text-warning" id="totalDisplay">Rp {{ number_format($subtotal+$tax+9000,0,',','.') }}</span></div>
                        </div>
                        <div class="mt-3 p-3 bg-base-200 rounded-lg text-sm">
                            <p class="text-base-content/60">Saldo Wallet</p>
                            <p class="font-bold {{ ($wallet && $wallet->balance>0) ? 'text-success' : 'text-error' }}">Rp {{ number_format($wallet->balance ?? 0,0,',','.') }}</p>
                        </div>
                        <button type="submit" class="btn btn-warning w-full mt-3" {{ $addresses->isEmpty()?'disabled':'' }}>Bayar Sekarang</button>
                        <p class="text-xs text-center text-base-content/50 mt-1">Pembayaran menggunakan saldo wallet SEAPEDIA</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const subtotal = {{ $cart->items->sum(fn($i) => $i->product->price * $i->quantity) }};
const tax = Math.round(subtotal * 0.12);
document.querySelectorAll('.delivery-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        const fee = parseInt(this.closest('.delivery-option').dataset.fee);
        document.getElementById('deliveryFeeDisplay').textContent = 'Rp ' + fee.toLocaleString('id-ID');
        document.getElementById('totalDisplay').textContent = 'Rp ' + (subtotal + tax + fee).toLocaleString('id-ID');
    });
});
</script>
@endsection
