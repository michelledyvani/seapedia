<?php $__env->startSection('title', $product->name); ?>
<?php $__env->startSection('content'); ?>
<div class="bg-base-200/30 border-b border-base-200">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="text-sm breadcrumbs text-base-content/60 font-medium">
            <ul>
                <li><a href="<?php echo e(route('home')); ?>" class="hover:text-warning transition-colors">Beranda</a></li>
                <li><a href="<?php echo e(route('products.index')); ?>" class="hover:text-warning transition-colors">Produk</a></li>
                <li class="text-base-content"><?php echo e(Str::limit($product->name, 30)); ?></li>
            </ul>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8 md:py-12">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12">
        <!-- Product Image -->
        <div class="md:col-span-5 lg:col-span-5 relative animate-fade-in">
            <div class="sticky top-24">
                <div class="relative bg-base-200/50 rounded-2xl overflow-hidden shadow-inner aspect-square flex items-center justify-center p-4">
                    <img src="<?php echo e($product->image_url ?? 'https://placehold.co/800x800?text=SEAPEDIA'); ?>" alt="<?php echo e($product->name); ?>" 
                         class="w-full h-full object-cover rounded-xl hover:scale-105 transition-transform duration-500 cursor-zoom-in" />
                    
                    <?php if($product->stock < 5 && $product->stock > 0): ?>
                        <div class="absolute top-4 left-4 badge badge-error shadow-sm font-bold border-none px-3 py-4 backdrop-blur-md bg-error/90">Sisa <?php echo e($product->stock); ?></div>
                    <?php endif; ?>
                </div>
                
                <!-- Thumbnails placeholder -->
                <div class="flex gap-2 mt-4 overflow-x-auto pb-2 scrollbar-hide">
                    <div class="w-16 h-16 rounded-lg bg-base-200 border-2 border-warning p-1 cursor-pointer shrink-0">
                        <img src="<?php echo e($product->image_url ?? 'https://placehold.co/100x100?text=1'); ?>" class="w-full h-full object-cover rounded-md" />
                    </div>
                    <?php for($i=2; $i<=4; $i++): ?>
                    <div class="w-16 h-16 rounded-lg bg-base-200 border border-base-300 p-1 cursor-pointer opacity-60 hover:opacity-100 transition-opacity shrink-0">
                        <img src="https://placehold.co/100x100?text=<?php echo e($i); ?>" class="w-full h-full object-cover rounded-md" />
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="md:col-span-7 lg:col-span-4 space-y-6 animate-slide-up" style="animation-delay: 0.1s;">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold font-heading mb-2 leading-tight"><?php echo e($product->name); ?></h1>
                <div class="flex items-center gap-4 text-sm mb-4">
                    <div class="flex items-center text-warning gap-1">
                        ★★★★★
                        <span class="text-base-content/60 ml-1">(Belum ada ulasan)</span>
                    </div>
                    <div class="w-1 h-1 rounded-full bg-base-300"></div>
                    <span class="text-base-content/60">Terjual 0</span>
                </div>
                
                <p class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-warning to-accent mb-2">Rp <?php echo e(number_format($product->price,0,',','.')); ?></p>
                
                <?php if($product->stock > 0): ?>
                    <div class="badge badge-success badge-sm mb-4">Tersedia (<?php echo e($product->stock); ?> stok)</div>
                <?php else: ?>
                    <div class="badge badge-error badge-sm mb-4">Stok Habis</div>
                <?php endif; ?>
            </div>

            <div class="divider my-0"></div>

            <div>
                <h3 class="font-bold mb-2">Deskripsi Produk</h3>
                <div class="prose prose-sm max-w-none text-base-content/80 leading-relaxed">
                    <?php echo nl2br(e($product->description)); ?>

                </div>
            </div>
            
            <div class="divider my-0"></div>
            
            <!-- Store Info -->
            <?php if($product->store): ?>
            <div class="bg-base-200/50 p-4 rounded-xl flex items-center justify-between border border-base-200">
                <div class="flex items-center gap-3">
                    <div class="avatar placeholder">
                        <div class="bg-gradient-to-br from-info to-primary text-white rounded-full w-12 shadow-sm">
                            <span class="text-lg">🏪</span>
                        </div>
                    </div>
                    <div>
                        <p class="font-bold text-base hover:text-info cursor-pointer transition-colors"><?php echo e($product->store->name); ?></p>
                        <p class="text-xs text-base-content/60 flex items-center gap-1">
                            <span class="text-success">●</span> Online Hari Ini
                        </p>
                    </div>
                </div>
                <a href="<?php echo e(route('stores.show', $product->store)); ?>" class="btn btn-sm btn-outline border-base-300 rounded-full font-normal hover:border-info hover:text-info">Kunjungi Toko</a>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Action Card -->
        <div class="md:col-span-12 lg:col-span-3 animate-slide-up" style="animation-delay: 0.2s;">
            <div class="card glass-card border border-base-200 sticky top-24 shadow-xl">
                <div class="card-body p-5">
                    <h3 class="font-bold text-lg mb-4">Atur Jumlah</h3>
                    
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(session('active_role') === 'buyer'): ?>
                            <?php if($product->stock > 0): ?>
                            <form action="<?php echo e(route('buyer.cart.add')); ?>" method="POST" class="space-y-4">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>" />
                                
                                <div class="flex items-center gap-3">
                                    <div class="join border border-base-300 rounded-lg">
                                        <button type="button" class="btn btn-sm join-item bg-base-100" onclick="document.getElementById('qty').stepDown()">-</button>
                                        <input type="number" id="qty" name="quantity" value="1" min="1" max="<?php echo e($product->stock); ?>" class="input input-sm join-item w-16 text-center font-bold bg-base-100 focus:outline-none focus:border-none" />
                                        <button type="button" class="btn btn-sm join-item bg-base-100" onclick="document.getElementById('qty').stepUp()">+</button>
                                    </div>
                                    <span class="text-sm text-base-content/60 font-medium">Stok: <?php echo e($product->stock); ?></span>
                                </div>
                                
                                <div class="flex items-center justify-between py-2 border-t border-b border-base-200">
                                    <span class="text-sm text-base-content/60">Subtotal</span>
                                    <span class="font-bold text-warning text-lg" id="subtotal">Rp <?php echo e(number_format($product->price,0,',','.')); ?></span>
                                </div>
                                
                                <div class="flex flex-col gap-2 pt-2">
                                    <button type="submit" class="btn btn-warning w-full rounded-full shadow-lg shadow-warning/20 hover:shadow-warning/40 font-bold border-none">
                                        + Keranjang
                                    </button>
                                    <button type="button" class="btn btn-outline w-full rounded-full border-base-300">
                                        Beli Langsung
                                    </button>
                                </div>
                            </form>
                            <?php else: ?>
                            <div class="alert alert-error text-sm rounded-xl py-2">Stok produk ini sedang kosong.</div>
                            <button class="btn btn-disabled w-full rounded-full mt-4">Tidak Tersedia</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="bg-info/10 text-info p-3 rounded-xl text-sm mb-4 border border-info/20">
                                Aktifkan role <strong>Buyer</strong> untuk mulai berbelanja.
                            </div>
                            <a href="<?php echo e(route('role.select')); ?>" class="btn btn-info w-full rounded-full shadow-sm text-white border-none">Ganti Role ke Buyer</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-sm text-base-content/60 mb-4">Silakan masuk ke akunmu untuk menambahkan barang ke keranjang.</p>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-warning w-full rounded-full shadow-sm font-bold border-none mb-2">Masuk</a>
                            <a href="<?php echo e(route('register')); ?>" class="text-sm text-warning font-semibold hover:text-accent transition-colors">Daftar Akun Baru</a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-4 pt-4 border-t border-base-200 flex justify-center gap-4">
                        <button class="btn btn-ghost btn-xs text-base-content/50 hover:text-error"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg> Wishlist</button>
                        <button class="btn btn-ghost btn-xs text-base-content/50 hover:text-info"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg> Bagikan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const price = <?php echo e($product->price); ?>;
    const qtyInput = document.getElementById('qty');
    const subtotalEl = document.getElementById('subtotal');
    
    if(qtyInput && subtotalEl) {
        qtyInput.addEventListener('input', function() {
            let val = parseInt(this.value) || 1;
            let max = parseInt(this.getAttribute('max'));
            let min = parseInt(this.getAttribute('min'));
            
            if(val > max) { this.value = max; val = max; }
            if(val < min) { this.value = min; val = min; }
            
            let total = val * price;
            subtotalEl.innerText = 'Rp ' + total.toLocaleString('id-ID');
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\seapedia\resources\views/public/products/show.blade.php ENDPATH**/ ?>