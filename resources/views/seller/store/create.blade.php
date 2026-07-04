@extends('layouts.app')
@section('title', 'Buat Toko')
@section('content')
<div class="max-w-xl mx-auto px-4 py-8">
    <div class="card bg-base-100 shadow border border-base-200">
        <div class="card-body">
            <h1 class="card-title text-xl mb-4">🏪 Buat Toko Baru</h1>
            @if($errors->any())<div class="alert alert-error text-sm mb-4">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
            <form action="{{ route('seller.store.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Nama Toko <span class="text-error">*</span></span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input input-bordered w-full" required maxlength="100" />
                    <label class="label"><span class="label-text-alt text-base-content/60">Nama toko harus unik.</span></label>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Deskripsi Toko</span></label>
                    <textarea name="description" rows="3" class="textarea textarea-bordered w-full">{{ old('description') }}</textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-warning flex-1">Buat Toko</button>
                    <a href="{{ route('seller.dashboard') }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
