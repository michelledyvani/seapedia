<?php $__env->startSection('title', 'Monitoring Order'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">📋 Semua Order</h1>
    <div class="overflow-x-auto">
        <table class="table bg-base-100 shadow rounded-xl">
            <thead><tr><th>ID</th><th>Buyer</th><th>Toko</th><th>Driver</th><th>Status</th><th>Total</th><th>Deadline SLA</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $badgeClass = match($o->status) {
                        'Sedang Dikemas'=>'badge-warning','Menunggu Pengirim'=>'badge-info','Sedang Dikirim'=>'badge-primary',
                        'Pesanan Selesai'=>'badge-success','Dikembalikan'=>'badge-error', default=>'badge-ghost',
                    };
                    $isOverdue = $o->overdue_at && $o->overdue_at->isPast() && !in_array($o->status, ['Pesanan Selesai','Dikembalikan']);
                ?>
                <tr class="<?php echo e($isOverdue ? 'bg-error/10' : ''); ?>">
                    <td>#<?php echo e($o->id); ?></td>
                    <td><?php echo e($o->buyer->name); ?></td>
                    <td><?php echo e($o->store->name); ?></td>
                    <td><?php echo e($o->driver->name ?? '-'); ?></td>
                    <td><span class="badge <?php echo e($badgeClass); ?>"><?php echo e($o->status); ?></span> <?php if($isOverdue): ?><span class="badge badge-error badge-xs ml-1">OVERDUE</span><?php endif; ?></td>
                    <td>Rp <?php echo e(number_format($o->total_amount,0,',','.')); ?></td>
                    <td class="text-xs"><?php echo e($o->overdue_at?->format('d M H:i')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4"><?php echo e($orders->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\seapedia\resources\views/admin/orders.blade.php ENDPATH**/ ?>