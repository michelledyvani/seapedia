@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-2">🔒 Demo Keamanan SEAPEDIA</h1>
    <p class="text-base-content/60 mb-8">Halaman ini untuk demo ke dosen penguji.</p>

    <div class="card bg-base-200 shadow mb-6">
        <div class="card-body">
            <h2 class="card-title text-success">✅ XSS Protection</h2>
            <p class="text-sm text-base-content/70 mb-3">
                Input berbahaya berikut disimpan tanpa filter di variabel PHP, lalu ditampilkan
                memakai Blade <code>{{ '{{ }}' }}</code> yang otomatis escape HTML.
            </p>
            @php $xss = '<script>alert("XSS Attack!")</script><img src=x onerror=alert(1)>'; @endphp
            <div class="bg-base-300 p-3 rounded-lg mb-2">
                <p class="text-xs opacity-50">Input jahat:</p>
                <code class="text-error text-sm">{{ $xss }}</code>
            </div>
            <div class="bg-success/10 border border-success p-3 rounded-lg">
                <p class="text-xs opacity-50">Ditampilkan di browser:</p>
                <p class="text-sm">{{ $xss }}</p>
                <p class="text-success text-xs mt-2">✅ Script tidak dieksekusi.</p>
            </div>
        </div>
    </div>

    <div class="card bg-base-200 shadow mb-6">
        <div class="card-body">
            <h2 class="card-title text-success">✅ SQL Injection Protection</h2>
            <p class="text-sm text-base-content/70">
                Semua query memakai Eloquent ORM (PDO prepared statements).
                Input seperti <code>' OR 1=1 --</code> diperlakukan sebagai string biasa,
                tidak bisa memodifikasi struktur query SQL.
            </p>
        </div>
    </div>

    <div class="card bg-base-200 shadow mb-6">
        <div class="card-body">
            <h2 class="card-title text-success">✅ CSRF Protection</h2>
            <p class="text-sm text-base-content/70 mb-2">
                Semua form POST/PUT/DELETE memakai token CSRF unik per sesi.
            </p>
            <code class="text-xs text-warning">{{ csrf_token() }}</code>
        </div>
    </div>

    <div class="card bg-base-200 shadow">
        <div class="card-body">
            <h2 class="card-title text-success">✅ Role-Based Access Control</h2>
            <p class="text-sm text-base-content/70">
                Middleware <code>role:{role}</code> memverifikasi <code>session('active_role')</code>
                di backend untuk setiap rute <code>/buyer/*</code>, <code>/seller/*</code>,
                <code>/driver/*</code>, dan <code>/admin/*</code> — tidak bisa di-bypass dari frontend.
            </p>
        </div>
    </div>
</div>
@endsection