<?php $__env->startSection('title', 'Beranda'); ?>
<?php $__env->startSection('content'); ?>


<section class="relative overflow-hidden" style="background: linear-gradient(135deg, #f0f5ff 0%, #e8f0fe 40%, #fef9ef 100%);">
    
    <div class="absolute top-10 right-10 w-20 h-20 bg-blue-100 rounded-full opacity-60 hidden lg:block"></div>
    <div class="absolute bottom-20 right-40 w-12 h-12 bg-amber-100 rounded-full opacity-60 hidden lg:block"></div>
    <div class="absolute top-32 right-72 w-8 h-8 bg-blue-200 rounded-full opacity-40 hidden lg:block"></div>

    <div class="max-w-7xl mx-auto px-4 py-16 md:py-24 relative z-10">
        <div class="max-w-2xl">
            <p class="text-primary font-semibold text-sm mb-3 tracking-wide">Marketplace Indonesia</p>

            <h1 class="text-4xl md:text-5xl lg:text-[3.5rem] font-extrabold leading-tight mb-5 text-neutral font-heading">
                Temukan & Jual<br>
                Produk <span class="text-primary">Terbaik</span>
            </h1>

            <p class="text-gray-500 text-lg mb-8 leading-relaxed max-w-lg">
                SEAPEDIA menghubungkan pembeli, penjual, dan driver di seluruh Indonesia dalam satu platform terintegrasi.
            </p>

            
            <form action="<?php echo e(route('products.index')); ?>" method="GET" class="flex items-center gap-3 mb-6 max-w-lg">
                <div class="relative flex-1">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" name="q" value="<?php echo e(request('q')); ?>"
                           placeholder="Cari produk, toko, atau kategori…"
                           class="w-full h-12 pl-11 pr-4 rounded-full border border-gray-200 bg-white shadow-sm focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none text-sm" />
                </div>
                <button type="submit" class="btn btn-primary h-12 px-6 rounded-full shadow-sm shadow-primary/20 font-semibold text-white">Cari</button>
            </form>

            <div class="flex gap-3">
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary btn-sm rounded-full px-5 shadow-sm shadow-primary/20 font-semibold text-white">Produk Unggulan</a>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-ghost btn-sm rounded-full px-5 border border-gray-200 text-gray-600 hover:bg-gray-50">Mulai Berjualan</a>
            </div>
        </div>
    </div>
</section>


<section class="bg-white py-10 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php $__currentLoopData = [
                ['🛒', 'Pembeli', 'Belanja produk terbaik', route('products.index')],
                ['🏪', 'Penjual', 'Buka & kelola tokomu', route('register')],
                ['🚚', 'Driver', 'Antar pesanan & hasilkan uang', route('register')],
                ['👑', 'Admin', 'Monitor seluruh marketplace', '#']
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($cat[3]); ?>" class="category-pill group">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl group-hover:bg-primary/10 transition-colors shrink-0">
                    <?php echo e($cat[0]); ?>

                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-neutral text-sm"><?php echo e($cat[1]); ?></p>
                    <p class="text-xs text-gray-400 truncate"><?php echo e($cat[2]); ?></p>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="bg-base-200/40 py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-neutral mb-3 font-heading">Produk Pilihan</h2>
            <p class="text-gray-500 max-w-lg mx-auto">Temukan produk terlaris dari toko terpercaya di seluruh Indonesia</p>
        </div>

        
        <div class="flex justify-center gap-2 mb-10">
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-sm btn-primary rounded-full px-5 font-medium text-white shadow-sm">Unggulan</a>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-sm btn-ghost rounded-full px-5 font-medium text-gray-500 hover:bg-gray-100">Terbaru</a>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-sm btn-ghost rounded-full px-5 font-medium text-gray-500 hover:bg-gray-100">Terlaris</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
            <?php $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('products.show', $product)); ?>" class="card-clean group overflow-hidden">
                <figure class="aspect-square bg-gray-50 p-3">
                    <img src="<?php echo e($product->image_url ?? 'https://placehold.co/300x300?text=SEAPEDIA'); ?>"
                         alt="<?php echo e($product->name); ?>"
                         class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition-transform duration-300"
                         loading="lazy" />
                </figure>
                <div class="p-4 pt-3">
                    <h3 class="text-sm font-semibold text-neutral line-clamp-2 leading-snug mb-1 group-hover:text-primary transition-colors"><?php echo e($product->name); ?></h3>
                    <p class="text-xs text-gray-400 mb-2 line-clamp-1"><?php echo e($product->store->name ?? 'SEAPEDIA Store'); ?></p>
                    <div class="flex items-center justify-between">
                        <span class="text-primary font-bold">Rp <?php echo e(number_format($product->price,0,',','.')); ?></span>
                        <div class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-300 hover:text-red-400 cursor-pointer transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            <div class="w-4 h-4 bg-primary rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="text-center mt-10">
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline btn-primary rounded-full px-8 font-semibold">Lihat Semua Produk →</a>
        </div>
    </div>
</section>


<?php if($latestReviews->count() > 0): ?>
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-neutral mb-3 font-heading">Kata Mereka</h2>
            <p class="text-gray-500">Ulasan dari pengguna yang sudah merasakan SEAPEDIA</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php $__currentLoopData = $latestReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card-clean p-6">
                <div class="flex gap-1 mb-3">
                    <?php for($i=1;$i<=5;$i++): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo e($i<=$review->rating ? 'text-amber-400 fill-current' : 'text-gray-200'); ?>" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    <?php endfor; ?>
                </div>
                <p class="text-sm text-gray-600 mb-4 line-clamp-3 leading-relaxed">"<?php echo e(e($review->comment)); ?>"</p>
                <div class="flex items-center gap-3 pt-3 border-t border-gray-50">
                    <div class="avatar placeholder">
                        <div class="bg-primary/10 text-primary rounded-full w-9 text-sm font-bold">
                            <span><?php echo e(strtoupper(substr($review->reviewer_name,0,1))); ?></span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-neutral"><?php echo e($review->reviewer_name); ?></p>
                        <p class="text-xs text-gray-400">Pengguna SEAPEDIA</p>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="text-center mt-8">
            <a href="<?php echo e(route('reviews.index')); ?>" class="btn btn-ghost btn-sm text-primary hover:bg-primary/5 rounded-full px-6">Semua Ulasan →</a>
        </div>
    </div>
</section>
<?php endif; ?>


<section class="bg-primary py-14">
    <div class="max-w-4xl mx-auto px-4 text-center text-white">
        <h2 class="text-3xl font-extrabold mb-3 font-heading">Siap Mulai Perjalananmu?</h2>
        <p class="mb-7 opacity-90">Daftar sekarang dan nikmati pengalaman berbelanja terbaik di Indonesia.</p>
        <a href="<?php echo e(route('register')); ?>" class="btn bg-white text-primary hover:bg-gray-50 border-none px-8 rounded-full font-bold shadow-lg">Daftar Gratis</a>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\seapedia\resources\views/public/home.blade.php ENDPATH**/ ?>