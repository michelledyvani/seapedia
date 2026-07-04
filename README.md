# SEAPEDIA — Multi-Role E-Commerce Marketplace

Submission untuk **COMPFEST 18 SEA — Software Engineering Academy**

Tech Stack: **Laravel 11** · **MySQL + Eloquent ORM** · **Blade** · **Tailwind CSS + DaisyUI**

---

## 📋 Daftar Isi

1. [Cara Setup & Menjalankan](#cara-setup--menjalankan)
2. [Akun Demo](#akun-demo)
3. [Single-Store Checkout](#single-store-checkout-rule)
4. [Aturan Diskon & PPN 12%](#aturan-diskon--ppn-12)
5. [Aturan Pendapatan Driver](#aturan-pendapatan-driver)
6. [Overdue SLA & Simulasi Waktu](#overdue-sla--simulasi-waktu)
7. [Keamanan](#keamanan)
8. [Panduan Testing End-to-End](#panduan-testing-end-to-end)

---

## Cara Setup & Menjalankan

### Requirement
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js >= 18 & NPM

### Build & Jalankan

```bash
# Compile assets
npm run build
# Atau untuk development dengan hot-reload:
npm run dev

# Jalankan server (di terminal terpisah)
php artisan serve
```

Buka `http://localhost:8000`

---


## Single-Store Checkout Rule

Karena SEAPEDIA adalah marketplace multi-seller, **satu keranjang (cart) hanya boleh berisi produk dari satu toko**.

**Implementasi:**
- Tabel `carts` punya kolom `store_id` yang diisi otomatis saat item pertama ditambahkan.
- Saat buyer mencoba menambah produk dari toko lain, `CartController::addItem()` mengecek `$cart->store_id !== $product->store_id` dan menolak permintaan dengan pesan error yang jelas.
- Buyer harus mengosongkan keranjang (`DELETE /buyer/cart/clear`) sebelum bisa belanja dari toko lain.
- Aturan ini ditampilkan secara eksplisit di halaman keranjang (`buyer/cart/index.blade.php`) dalam bentuk alert info.

---

## Aturan Diskon & PPN 12%

### Voucher vs Promo

| | Voucher | Promo |
|---|---|---|
| Batas pemakaian | Ya (`max_usage`, `used_count`) | Tidak ada batas |
| Expiry date | Ya | Ya |
| Tipe diskon | Persentase atau Nominal Tetap | Persentase atau Nominal Tetap |

### Kombinasi

Voucher dan Promo **bisa dikombinasikan** dalam satu checkout. Total diskon dari keduanya dijumlahkan, namun dibatasi (`min()`) agar tidak melebihi subtotal.

### Urutan Kalkulasi (PENTING)

```
1. Subtotal       = Σ (harga produk × kuantitas)
2. Discount       = voucher_discount + promo_discount  (dibatasi max = subtotal)
3. Tax Base       = Subtotal - Discount
4. PPN (12%)      = Tax Base × 0.12
5. Total          = Subtotal - Discount + PPN + Delivery Fee
```

**PPN dihitung SETELAH diskon diterapkan, SEBELUM ongkir ditambahkan.** Ongkir tidak dikenakan PPN.

### Biaya Pengiriman

| Metode | Biaya | SLA |
|---|---|---|
| Instant | Rp 25.000 | 1 hari |
| Next Day | Rp 15.000 | 2 hari |
| Regular | Rp 9.000 | 7 hari |

---

## Aturan Pendapatan Driver

Driver mendapatkan **100% dari `delivery_fee`** order yang berhasil diselesaikan (status `Pesanan Selesai`). Tidak ada potongan platform fee untuk versi ini (didokumentasikan agar transparan terhadap evaluator).

Perhitungan total pendapatan: `SUM(delivery_fee) WHERE driver_id = X AND status = 'Pesanan Selesai'`

---

## Overdue SLA & Simulasi Waktu

Setiap order menyimpan kolom `overdue_at` yang dihitung saat checkout: `created_at + SLA hari` sesuai metode pengiriman.

### Cara Simulasi

Ada **dua cara** untuk memproses order yang melewati SLA:

#### 1. Artisan Command (Recommended)

Jalankan command `CheckLateOrders` dari terminal:

```bash
php artisan orders:check-late
```

Command ini cocok untuk dijadwalkan via cron/task scheduler agar berjalan otomatis. Untuk menjadwalkan, tambahkan di `routes/console.php` atau `app/Console/Kernel.php`:

```php
Schedule::command('orders:check-late')->hourly();
```

#### 2. Admin Dashboard (Manual)

Admin bisa trigger manual via dashboard (`POST /admin/simulate-next-day`), tombol **"Simulate Next Day"**.

### Apa yang terjadi saat order diproses?

Sistem mencari semua order dengan `overdue_at < sekarang` DAN status BUKAN `Pesanan Selesai`/`Dikembalikan` DAN `refunded = false`.

Untuk setiap order overdue (dibungkus `DB::transaction()` agar atomic):

1. **Status diubah** → `Dikembalikan`, dicatat di `order_status_histories`.
2. **Refund buyer** → Saldo wallet buyer dikembalikan sebesar `total_amount` (dicatat di `wallet_transactions` sebagai tipe `refund`).
3. **Stok dikembalikan** → Kuantitas setiap produk di order dikembalikan ke stok.
4. **Pendapatan seller dideduct** → `subtotal` order dikurangkan dari wallet seller (reversal pendapatan).
5. Kolom `refunded` di-set `true` untuk **mencegah double refund**.

Logika Artisan command ada di `App\Console\Commands\CheckLateOrders`.
Logika admin dashboard ada di `App\Http\Controllers\Admin\MonitoringController::simulateNextDay()`.

---

## Keamanan

### SQL Injection
Seluruh query menggunakan **Eloquent ORM** dan Query Builder Laravel yang otomatis melakukan parameter binding. Tidak ada raw SQL string concatenation di manapun dalam kode ini.

### XSS (Cross-Site Scripting)
- Input dari user (nama, komentar review, deskripsi toko/produk, dll) di-`strip_tags()` sebelum disimpan ke database.
- Saat ditampilkan di Blade, semua output menggunakan `{{ }}` (bukan `{!! !!}`) yang otomatis melakukan `htmlspecialchars()` — mencegah script tag dieksekusi di browser.
- Khusus untuk review aplikasi, eksplisit memakai `e()` helper untuk escape tambahan.

### Validasi Input
Semua form divalidasi server-side menggunakan Laravel Validation Rules (`required`, `email`, `numeric`, `min`, `max`, `unique`, dll) sebelum data disimpan.

### Role-Based Access Control (RBAC)
- Setiap endpoint privat dilindungi middleware `role:xxx` (`App\Http\Middleware\CheckActiveRole`).
- Middleware memeriksa `session('active_role')`, BUKAN sekadar daftar role yang dimiliki user — sehingga user dengan multi-role harus pilih role aktif dulu.
- Setiap controller juga melakukan pengecekan kepemilikan resource secara eksplisit di level method (contoh: `Seller\ProductController::update()` memverifikasi `$product->store_id === $store->id` sebelum mengizinkan update), bukan hanya mengandalkan middleware.

### Session
- Setelah login, `$request->session()->regenerate()` dipanggil untuk mencegah **session fixation attack**.
- Setelah logout, `$request->session()->invalidate()` dan `regenerateToken()` dipanggil untuk membersihkan sesi sepenuhnya.
- `SESSION_LIFETIME` diset 120 menit (dapat dikonfigurasi di `.env`).

### Password
Password di-hash menggunakan `Hash::make()` (bcrypt) — tidak pernah disimpan dalam bentuk plain text.

---

## Panduan Testing End-to-End

### 1. Guest Flow
1. Buka halaman utama tanpa login → bisa lihat produk & ulasan.
2. Coba submit ulasan aplikasi tanpa login → berhasil.
3. Coba akses `/buyer/dashboard` tanpa login → redirect ke login.

### 2. Auth & Multi-Role Flow
1. Login sebagai `multi@seapedia.id` → diarahkan ke halaman pilih role (karena punya 3 role).
2. Pilih "Buyer" → masuk dashboard buyer.
3. Logout, login lagi, pilih "Seller" → masuk dashboard seller (toko berbeda dari sesi sebelumnya).

### 3. Seller Flow
1. Login sebagai `seller@seapedia.id`.
2. Coba buat toko dengan nama yang sudah dipakai seller lain → ditolak (unique constraint).
3. Tambah produk baru → cek muncul di katalog publik.
4. Edit/hapus produk → hanya bisa untuk produk milik toko sendiri.

### 4. Buyer Flow (Checkout Lengkap)
1. Login sebagai `buyer@seapedia.id`.
2. Tambah produk dari Toko A ke cart.
3. Coba tambah produk dari Toko B → **ditolak** (single-store rule).
4. Lanjut ke checkout, pilih alamat & metode pengiriman.
5. Masukkan kode voucher `COMPFEST10` → cek diskon 10% muncul.
6. Selesaikan pembayaran → cek saldo wallet berkurang, stok produk berkurang, order muncul di riwayat.

### 5. Seller Process Order
1. Login sebagai seller pemilik toko dari order di atas.
2. Buka "Pesanan Masuk" → klik "Proses Pesanan" → status berubah ke "Menunggu Pengirim".

### 6. Driver Flow
1. Login sebagai `driver@seapedia.id`.
2. Buka "Cari Lowongan" → order yang baru diproses seller harus muncul.
3. Ambil pesanan → status order berubah "Sedang Dikirim", driver lain tidak bisa ambil order yang sama.
4. Konfirmasi selesai → status "Pesanan Selesai", pendapatan driver bertambah.

### 7. Admin Flow
1. Login sebagai `admin@seapedia.id`.
2. Cek dashboard monitoring menampilkan semua statistik.
3. Buat voucher/promo baru.
4. Untuk test overdue: ubah manual `overdue_at` salah satu order di database ke masa lalu, lalu klik "Simulate Next Day" → cek saldo buyer kembali, stok kembali, status jadi "Dikembalikan".

### 8. Security Test
1. Coba submit `<script>alert('xss')</script>` di form ulasan aplikasi → harus tampil sebagai teks biasa, bukan dieksekusi.
2. Coba akses `/seller/products/{id}/edit` untuk produk milik seller lain → harus 403.
3. Logout lalu coba kembali ke halaman dashboard (tombol back browser) → harus redirect ke login.

---

## Struktur Folder Penting

```
app/
├── Console/Commands/
│   └── CheckLateOrders.php # Artisan command: auto-refund overdue orders
├── Http/Controllers/
│   ├── Auth/              # Register, Login, Logout
│   ├── Buyer/             # Wallet, Address, Cart, Checkout
│   ├── Seller/             # Store, Product, Order
│   ├── Driver/             # Job (find, take, complete)
│   └── Admin/              # Discount, Monitoring
├── Models/                 # Semua Eloquent Model
└── Http/Middleware/
    └── CheckActiveRole.php # RBAC berdasarkan active_role di session

database/
├── migrations/             # Semua skema tabel
└── seeders/
    └── DatabaseSeeder.php  # Demo data lengkap

resources/views/
├── layouts/app.blade.php   # Layout utama (navbar, dark mode, footer)
├── public/                 # Home, products, reviews, stores
├── auth/                   # Login, register
├── role/                   # Role selection
├── dashboard/              # Dashboard shells per role
├── buyer/                  # Wallet, address, cart, checkout, orders
├── seller/                 # Store, products, orders
├── driver/                 # Jobs, dashboard
└── admin/                  # Discounts, dashboard, orders, users

routes/web.php               # Semua route aplikasi
```

## 🔒 Catatan Keamanan (Level 7)

- **SQL Injection:** Semua query database menggunakan Eloquent ORM yang otomatis menggunakan PDO prepared statements. Tidak ada raw query dengan string concatenation.
- **XSS:** Output user-generated content (ulasan, nama toko, deskripsi produk) ditampilkan dengan Blade `{{ }}` yang otomatis escape HTML. Input juga di-`strip_tags()` di server sebelum disimpan (double-layer protection).
- **CSRF:** Seluruh form POST/PUT/DELETE memakai `@csrf` token bawaan Laravel.
- **Role-Based Access Control:** Role aktif disimpan di `session('active_role')` dan diverifikasi ulang di backend lewat middleware `CheckActiveRole` pada setiap request — bukan hanya dicek di frontend.
- **Session Security:** Session di-regenerate setelah login (`session()->regenerate()`) dan di-invalidate saat logout (`session()->invalidate()`) untuk mencegah session fixation/hijacking.
- **Kepemilikan Resource:** Setiap aksi update/delete (produk, toko, pesanan, alamat) memverifikasi `user_id`/`store_id` milik user yang login sebelum mengizinkan perubahan.

**Demo:** Kunjungi `/security-demo` untuk melihat bukti proteksi XSS, SQLi, CSRF, dan RBAC secara langsung.

## 📊 Catatan Perhitungan Bisnis

- **PPN 12%** dihitung dari `(subtotal − diskon) × 12%`. Ongkir **tidak** dikenakan PPN.
- **Total akhir** = `(subtotal − diskon) + PPN + ongkir`.
- **Pendapatan Driver** = `delivery_fee` pada order yang berstatus "Pesanan Selesai", dihitung on-the-fly (bukan disimpan terpisah di wallet).
- **Refund otomatis** terjadi saat `overdue_at` terlewati dan order belum `Pesanan Selesai`/`Dikembalikan`. Saldo dikembalikan ke wallet buyer, stok dikembalikan, dan kolom `refunded` mencegah refund ganda.

---

## Catatan Tambahan

- Desain visual mengacu pada platform e-commerce populer (Amazon, Tokopedia) dengan search bar prominent, kartu produk grid, dan navigasi kategori.
- Mode terang/gelap (light/dark) didukung secara global menggunakan DaisyUI theme + localStorage persistence.
- Semua transaksi finansial kritis (checkout, refund) dibungkus `DB::transaction()` untuk menjamin atomicity.
