<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <span class="text-3xl">👑</span>
        <div><h1 class="text-2xl font-bold">Admin Monitoring Dashboard</h1><p class="text-sm text-base-content/60">Pantau seluruh aktivitas SEAPEDIA</p></div>
        <div class="ml-auto badge badge-warning badge-lg">ADMIN</div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Users</div><div class="stat-value text-2xl"><?php echo e($stats['users']); ?></div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Stores</div><div class="stat-value text-2xl"><?php echo e($stats['stores']); ?></div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Products</div><div class="stat-value text-2xl"><?php echo e($stats['products']); ?></div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Orders</div><div class="stat-value text-2xl"><?php echo e($stats['orders']); ?></div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Vouchers</div><div class="stat-value text-2xl"><?php echo e($stats['vouchers']); ?></div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Promos</div><div class="stat-value text-2xl"><?php echo e($stats['promos']); ?></div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border border-base-200">
            <div class="stat-title">Driver Aktif</div><div class="stat-value text-2xl"><?php echo e($stats['active_drivers']); ?></div>
        </div>
        <div class="stat bg-base-100 rounded-xl shadow border-2 border-error">
            <div class="stat-title text-error">Overdue Orders</div><div class="stat-value text-2xl text-error"><?php echo e($stats['overdue']); ?></div>
        </div>
    </div>

    <div class="flex gap-3 flex-wrap mb-6">
        <a href="<?php echo e(route('admin.orders')); ?>" class="btn btn-outline btn-sm">📋 Semua Order</a>
        <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-outline btn-sm">👥 Semua User</a>
        <a href="<?php echo e(route('admin.discounts')); ?>" class="btn btn-outline btn-sm">🎟 Kelola Diskon</a>
    </div>

    
    <div class="card bg-base-100 border-2 border-warning shadow mb-6">
        <div class="card-body">
            <h2 class="card-title">⏰ Simulasi Hari Berikutnya</h2>
            <p class="text-sm text-base-content/70">
                Trigger ini akan mencari semua order yang sudah melewati SLA pengiriman (Instant: 1 hari, Next Day: 2 hari, Regular: 7 hari)
                dan belum selesai/dikembalikan, lalu otomatis: mengembalikan saldo ke wallet buyer, mengembalikan stok produk,
                dan mengubah status order menjadi <strong>Dikembalikan</strong>.
            </p>
            <?php if(count($overdueOrders) > 0): ?>
                <div class="alert alert-warning text-sm mt-2">
                    Ditemukan <strong><?php echo e(count($overdueOrders)); ?></strong> order yang overdue dan siap diproses.
                </div>
                <div class="overflow-x-auto mt-2">
                    <table class="table table-sm">
                        <thead><tr><th>Order</th><th>Buyer</th><th>Toko</th><th>Total</th><th>Deadline</th></tr></thead>
                        <tbody>
                            <?php $__currentLoopData = $overdueOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>#<?php echo e($o->id); ?></td><td><?php echo e($o->buyer->name); ?></td><td><?php echo e($o->store->name); ?></td>
                                <td>Rp <?php echo e(number_format($o->total_amount,0,',','.')); ?></td>
                                <td class="text-error"><?php echo e($o->overdue_at->format('d M Y H:i')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-sm text-success mt-2">✅ Tidak ada order overdue saat ini.</p>
            <?php endif; ?>
            <form action="<?php echo e(route('admin.simulate')); ?>" method="POST" class="mt-3" onsubmit="return confirm('Jalankan simulasi hari berikutnya? Ini akan memproses semua order overdue.')">
                <?php echo csrf_field(); ?>
                <button class="btn btn-warning" <?php echo e(count($overdueOrders) === 0 ? 'disabled' : ''); ?>>
                    ⏭ Simulate Next Day
                </button>
            </form>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-3">Order Terbaru</h2>
    <div class="overflow-x-auto">
        <table class="table bg-base-100 shadow rounded-xl">
            <thead><tr><th>Order</th><th>Buyer</th><th>Toko</th><th>Status</th><th>Total</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>#<?php echo e($o->id); ?></td><td><?php echo e($o->buyer->name); ?></td><td><?php echo e($o->store->name); ?></td>
                    <td><span class="badge badge-sm"><?php echo e($o->status); ?></span></td>
                    <td>Rp <?php echo e(number_format($o->total_amount,0,',','.')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\seapedia\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>