<?php $__env->startSection('title', 'Dashboard Buyer'); ?>
<?php $__env->startSection('content'); ?>
<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="avatar placeholder">
                    <div class="bg-blue-600 text-white rounded-full w-14">
                        <span class="text-xl font-bold"><?php echo e(strtoupper(substr(Auth::user()->name,0,1))); ?></span>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold font-heading text-gray-900">Halo, <?php echo e(Auth::user()->name); ?>! 👋</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Selamat datang kembali di Dashboard Pembeli.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 bg-gray-50 p-2 rounded-xl border border-gray-100">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-xs font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    BUYER
                </span>
                <a href="<?php echo e(route('role.select')); ?>" class="text-xs text-gray-400 hover:text-blue-600 transition-colors duration-200 px-2">Ganti Role</a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-medium text-gray-500">Saldo Wallet</h2>
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-blue-600 mb-4">Rp <?php echo e(number_format($wallet->balance,0,',','.')); ?></p>
            <a href="<?php echo e(route('buyer.wallet')); ?>" class="block w-full text-center py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors duration-200">Top Up Saldo</a>
        </div>

        
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-medium text-gray-500">Total Pesanan</h2>
                <div class="p-2 bg-gray-50 text-gray-500 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-gray-900 mb-1"><?php echo e($orders); ?></p>
            <p class="text-sm text-gray-400 mb-4">transaksi</p>
            <a href="<?php echo e(route('buyer.orders')); ?>" class="block w-full text-center py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200">Riwayat Pesanan</a>
        </div>

        
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:-translate-y-1 transition-all duration-200">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-medium text-gray-500">Alamat Tersimpan</h2>
                <div class="p-2 bg-gray-50 text-gray-500 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
            </div>
            <p class="text-3xl font-extrabold text-gray-900 mb-1"><?php echo e(Auth::user()->addresses()->count()); ?></p>
            <p class="text-sm text-gray-400 mb-4">lokasi</p>
            <a href="<?php echo e(route('buyer.addresses')); ?>" class="block w-full text-center py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-50 transition-colors duration-200">Kelola Alamat</a>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h2 class="text-lg font-bold mb-4 font-heading flex items-center gap-2 text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            Akses Cepat
        </h2>
        <div class="flex gap-4 flex-wrap">
            <a href="<?php echo e(route('products.index')); ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-full transition-colors duration-200">
                Mulai Belanja
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
            <a href="<?php echo e(route('buyer.cart')); ?>" class="inline-flex items-center gap-2 px-6 py-2.5 border border-gray-200 text-gray-700 text-sm font-semibold rounded-full hover:bg-gray-50 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                Lihat Keranjang
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\seapedia\resources\views/dashboard/buyer.blade.php ENDPATH**/ ?>