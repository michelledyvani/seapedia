<?php $__env->startSection('title', 'Pilih Role'); ?>
<?php $__env->startSection('content'); ?>
<div class="min-h-[80vh] flex items-center justify-center bg-base-200 py-8 px-4">
    <div class="card bg-base-100 shadow-xl w-full max-w-md">
        <div class="card-body text-center">
            <h2 class="text-2xl font-bold mb-2">Kamu Mau Jadi Apa Hari Ini?</h2>
            <p class="text-base-content/60 text-sm mb-6">Akunmu memiliki lebih dari satu peran. Pilih peran untuk sesi ini.</p>

            <?php if($errors->any()): ?>
                <div class="alert alert-error text-sm mb-4"><?php echo e($errors->first()); ?></div>
            <?php endif; ?>

            <form action="<?php echo e(route('role.set')); ?>" method="POST" class="space-y-3">
                <?php echo csrf_field(); ?>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $icons  = ['buyer'=>'🛒','seller'=>'🏪','driver'=>'🚚','admin'=>'👑'];
                    $labels = ['buyer'=>'Pembeli','seller'=>'Penjual','driver'=>'Driver','admin'=>'Admin'];
                    $descs  = ['buyer'=>'Belanja produk, kelola keranjang & alamat','seller'=>'Kelola toko dan produk','driver'=>'Ambil dan antar pesanan','admin'=>'Monitor dan kelola marketplace'];
                ?>
                <button type="submit" name="role" value="<?php echo e($role); ?>" class="btn btn-outline w-full flex items-center gap-3 h-auto py-3 hover:btn-warning">
                    <span class="text-2xl"><?php echo e($icons[$role] ?? '👤'); ?></span>
                    <div class="text-left">
                        <p class="font-bold capitalize"><?php echo e($labels[$role] ?? $role); ?></p>
                        <p class="text-xs font-normal opacity-70"><?php echo e($descs[$role] ?? ''); ?></p>
                    </div>
                </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\seapedia\resources\views/role/select.blade.php ENDPATH**/ ?>