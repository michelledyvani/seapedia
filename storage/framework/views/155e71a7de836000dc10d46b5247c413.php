<?php $__env->startSection('title', 'Semua Produk'); ?>
<?php $__env->startSection('content'); ?>

<div class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <div class="text-sm breadcrumbs text-gray-400">
            <ul>
                <li><a href="<?php echo e(route('home')); ?>" class="hover:text-primary">Beranda</a></li>
                <li class="text-neutral font-medium">Semua Produk</li>
            </ul>
        </div>
    </div>
</div>

<div class="bg-base-200/30 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-neutral font-heading">
                    <?php if(request('q')): ?>
                        Hasil pencarian: <span class="text-primary">"<?php echo e(request('q')); ?>"</span>
                    <?php else: ?>
                        Semua Produk
                    <?php endif; ?>
                </h1>
                <p class="text-sm text-gray-400 mt-1">Menampilkan <?php echo e($products->count()); ?> dari <?php echo e($products->total()); ?> produk</p>
            </div>

            <div class="flex gap-2">
                <select class="select select-sm select-bordered rounded-full bg-white border-gray-200 text-sm font-normal">
                    <option disabled selected>Urutkan</option>
                    <option>Terbaru</option>
                    <option>Harga Terendah</option>
                    <option>Harga Tertinggi</option>
                </select>
            </div>
        </div>

        <?php if($products->isEmpty()): ?>
            <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
                <div class="w-20 h-20 bg-gray-50 rounded-full mx-auto flex items-center justify-center text-4xl mb-5">🔍</div>
                <h2 class="text-xl font-bold text-neutral mb-2">Produk tidak ditemukan</h2>
                <p class="text-gray-400 max-w-sm mx-auto mb-5">Coba kata kunci lain atau telusuri kategori yang tersedia.</p>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary btn-sm rounded-full px-6 text-white">Lihat Semua</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('products.show', $product)); ?>" class="card-clean group overflow-hidden">
                    <figure class="aspect-square bg-gray-50 p-3 relative">
                        <img src="<?php echo e($product->image_url ?? 'https://placehold.co/300x300?text=SEAPEDIA'); ?>"
                             alt="<?php echo e($product->name); ?>"
                             class="w-full h-full object-cover rounded-xl group-hover:scale-105 transition-transform duration-300"
                             loading="lazy" />
                        <?php if($product->stock < 5 && $product->stock > 0): ?>
                            <div class="absolute top-4 right-4 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Sisa <?php echo e($product->stock); ?></div>
                        <?php elseif($product->stock == 0): ?>
                            <div class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center rounded-xl m-3">
                                <span class="bg-gray-800 text-white text-xs font-bold px-3 py-1 rounded-full">Habis</span>
                            </div>
                        <?php endif; ?>
                    </figure>
                    <div class="p-4 pt-3">
                        <p class="text-xs text-gray-400 mb-1 line-clamp-1"><?php echo e($product->store ? $product->store->name : 'SEAPEDIA'); ?></p>
                        <h3 class="text-sm font-semibold text-neutral line-clamp-2 leading-snug mb-2 group-hover:text-primary transition-colors"><?php echo e($product->name); ?></h3>
                        <div class="flex items-center justify-between">
                            <span class="text-primary font-bold">Rp <?php echo e(number_format($product->price,0,',','.')); ?></span>
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                <div class="w-4 h-4 bg-primary rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="mt-10 flex justify-center">
                <?php echo e($products->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\seapedia\resources\views/public/products/index.blade.php ENDPATH**/ ?>