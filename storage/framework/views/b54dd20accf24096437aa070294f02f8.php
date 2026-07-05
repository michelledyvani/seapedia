<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>SEAPEDIA — <?php echo $__env->yieldContent('title', 'Marketplace Indonesia'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script>
        (function() {
            const saved = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>
</head>
<body class="min-h-screen bg-base-100 text-base-content flex flex-col font-sans antialiased">

    
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <nav class="flex items-center justify-between h-16 gap-6">
                
                <a href="<?php echo e(route('home')); ?>" class="text-2xl font-extrabold tracking-tight shrink-0 font-heading text-neutral">
                    <span class="text-primary">SEA</span>PEDIA
                </a>

                
                <div class="hidden lg:flex items-center gap-1 text-sm font-medium text-gray-600">
                    <a href="<?php echo e(route('home')); ?>" class="px-3 py-2 rounded-lg hover:bg-base-200 hover:text-primary transition-colors">Home</a>
                    <a href="<?php echo e(route('products.index')); ?>" class="px-3 py-2 rounded-lg hover:bg-base-200 hover:text-primary transition-colors">Produk</a>
                    <a href="<?php echo e(route('reviews.index')); ?>" class="px-3 py-2 rounded-lg hover:bg-base-200 hover:text-primary transition-colors">Ulasan</a>

                    <?php if(auth()->guard()->check()): ?>
                        <?php if(session('active_role')): ?>
                            <a href="<?php echo e(route('dashboard.' . session('active_role'))); ?>" class="px-3 py-2 rounded-lg hover:bg-base-200 hover:text-primary transition-colors">Dashboard</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                
                <div class="flex items-center gap-3 flex-1 lg:flex-none justify-end">
                    
                    <form action="<?php echo e(route('products.index')); ?>" method="GET" class="hidden md:flex items-center relative max-w-xs w-full">
                        <input type="text" name="q" value="<?php echo e(request('q')); ?>"
                               placeholder="Cari produk…"
                               class="input input-sm input-bordered w-full rounded-full pl-9 bg-base-200/60 border-gray-200 focus:border-primary focus:bg-white focus:outline-none text-sm h-9" />
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </form>

                    
                    <button id="themeToggle" class="btn btn-ghost btn-sm btn-circle text-gray-500" type="button" aria-label="Toggle Theme">
                        <svg id="iconSun" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <svg id="iconMoon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(session('active_role') === 'buyer'): ?>
                            <a href="<?php echo e(route('buyer.cart')); ?>" class="btn btn-ghost btn-sm btn-circle text-gray-500 hover:text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    
                    <?php if(auth()->guard()->check()): ?>
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="btn btn-ghost btn-sm gap-2 normal-case rounded-full border border-gray-200 hover:border-primary/40 px-3 h-9">
                                <div class="avatar placeholder">
                                    <div class="bg-primary text-primary-content rounded-full w-6 text-xs">
                                        <span><?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?></span>
                                    </div>
                                </div>
                                <span class="text-sm font-medium hidden sm:inline"><?php echo e(Auth::user()->name); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </label>
                            <ul tabindex="0" class="dropdown-content menu p-2 shadow-lg bg-white rounded-xl w-52 mt-2 z-50 border border-gray-100">
                                <?php if(session('active_role')): ?>
                                    <li class="px-3 py-1.5"><span class="text-xs text-gray-400 uppercase tracking-wider font-semibold"><?php echo e(session('active_role')); ?></span></li>
                                    <li><a href="<?php echo e(route('dashboard.' . session('active_role'))); ?>" class="text-sm">Dashboard</a></li>
                                <?php endif; ?>
                                <li><a href="<?php echo e(route('role.select')); ?>" class="text-sm">Ganti Role</a></li>
                                <div class="divider my-1"></div>
                                <li>
                                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="p-0">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="w-full text-left text-sm text-error hover:bg-error/5 px-4 py-2 rounded-lg">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-ghost btn-sm font-medium text-gray-600 hidden sm:inline-flex">Masuk</a>
                            <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-sm font-semibold rounded-full px-5 shadow-sm shadow-primary/20 text-white">Daftar</a>
                        </div>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    
    <?php if(session('success')): ?>
        <div class="bg-emerald-50 border-b border-emerald-100 text-emerald-700 text-sm font-medium text-center py-2.5 px-4 animate-fade-in">
            ✓ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="bg-red-50 border-b border-red-100 text-red-600 text-sm font-medium text-center py-2.5 px-4 animate-fade-in">
            ✕ <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <main class="flex-1">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <footer class="bg-white border-t border-gray-100 mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-14">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-10">
                
                <div class="col-span-2 md:col-span-1 lg:col-span-1">
                    <a href="<?php echo e(route('home')); ?>" class="text-2xl font-extrabold tracking-tight font-heading text-neutral mb-4 block">
                        <span class="text-primary">SEA</span>PEDIA
                    </a>
                    <p class="text-sm text-gray-500 leading-relaxed mb-5">
                        Platform marketplace terdepan di Indonesia.
                    </p>
                    <div class="flex gap-3">
                        <a href="#" class="w-9 h-9 rounded-full bg-base-200 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
                        <a href="#" class="w-9 h-9 rounded-full bg-base-200 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg></a>
                        <a href="#" class="w-9 h-9 rounded-full bg-base-200 flex items-center justify-center text-gray-400 hover:bg-primary hover:text-white transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg></a>
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold text-neutral mb-4 text-sm">Home</h3>
                    <ul class="space-y-2.5 text-sm text-gray-500">
                        <li><a href="<?php echo e(route('home')); ?>" class="hover:text-primary transition-colors">Beranda</a></li>
                        <li><a href="<?php echo e(route('products.index')); ?>" class="hover:text-primary transition-colors">Produk Baru</a></li>
                        <li><a href="<?php echo e(route('reviews.index')); ?>" class="hover:text-primary transition-colors">Ulasan</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-neutral mb-4 text-sm">Layanan</h3>
                    <ul class="space-y-2.5 text-sm text-gray-500">
                        <li><a href="<?php echo e(route('products.index')); ?>" class="hover:text-primary transition-colors">Belanja</a></li>
                        <li><a href="<?php echo e(route('register')); ?>" class="hover:text-primary transition-colors">Mulai Berjualan</a></li>
                        <li><a href="<?php echo e(route('register')); ?>" class="hover:text-primary transition-colors">Mitra Driver</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Promo</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-neutral mb-4 text-sm">Kategori</h3>
                    <ul class="space-y-2.5 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-primary transition-colors">Elektronik</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Pakaian</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Furnitur</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Otomotif</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-semibold text-neutral mb-4 text-sm">Bantuan</h3>
                    <ul class="space-y-2.5 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-primary transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-primary transition-colors">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 py-5 flex flex-col md:flex-row justify-between items-center gap-3 text-xs text-gray-400">
                <p>&copy; <?php echo e(date('Y')); ?> PT SEAPEDIA Nusantara. Hak Cipta Dilindungi.</p>
                <p>Dibuat dengan ❤ untuk COMPFEST 18</p>
            </div>
        </div>
    </footer>

    
    <script>
        const toggle = document.getElementById('themeToggle');
        const iconSun = document.getElementById('iconSun');
        const iconMoon = document.getElementById('iconMoon');
        const html = document.documentElement;

        function updateIcons() {
            const theme = html.getAttribute('data-theme');
            if (theme === 'dark') {
                iconSun.classList.remove('hidden');
                iconMoon.classList.add('hidden');
            } else {
                iconSun.classList.add('hidden');
                iconMoon.classList.remove('hidden');
            }
        }

        updateIcons();

        toggle.addEventListener('click', () => {
            const current = html.getAttribute('data-theme');
            const next = current === 'light' ? 'dark' : 'light';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateIcons();
        });
    </script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\seapedia\resources\views/layouts/app.blade.php ENDPATH**/ ?>