<?php $__env->startSection('title', 'Ulasan Aplikasi'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-2">Ulasan Aplikasi SEAPEDIA</h1>
    <p class="text-base-content/60 text-sm mb-6">Bagikan pengalamanmu menggunakan SEAPEDIA. Tidak perlu checkout untuk memberi ulasan.</p>

    <div class="card bg-base-100 shadow border border-base-200 mb-8">
        <div class="card-body">
            <h2 class="card-title text-lg">Tulis Ulasanmu</h2>
            <?php if($errors->any()): ?>
                <div class="alert alert-error text-sm"><ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
            <?php endif; ?>
            <form action="<?php echo e(route('reviews.store')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Nama Kamu</span></label>
                    <input type="text" name="reviewer_name" value="<?php echo e(old('reviewer_name')); ?>" class="input input-bordered w-full" required maxlength="100" />
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Rating</span></label>
                    <div class="rating rating-lg">
                        <?php for($i=1;$i<=5;$i++): ?>
                        <input type="radio" name="rating" value="<?php echo e($i); ?>" class="mask mask-star-2 bg-warning" <?php echo e($i==5?'checked':''); ?> />
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Komentar</span></label>
                    <textarea name="comment" rows="4" class="textarea textarea-bordered w-full" required maxlength="1000"><?php echo e(old('comment')); ?></textarea>
                </div>
                <button type="submit" class="btn btn-warning">Kirim Ulasan</button>
            </form>
        </div>
    </div>

    <h2 class="text-xl font-bold mb-4"><?php echo e($reviews->count()); ?> Ulasan</h2>
    <?php if($reviews->isEmpty()): ?>
        <div class="text-center py-12 text-base-content/50"><p class="text-3xl mb-2">💬</p><p>Belum ada ulasan.</p></div>
    <?php else: ?>
        <div class="space-y-4">
            <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card bg-base-100 border border-base-200 shadow-sm">
                <div class="card-body py-4 flex-row items-start gap-3">
                    <div class="avatar placeholder shrink-0">
                        <div class="bg-warning text-warning-content rounded-full w-10">
                            <span class="font-bold"><?php echo e(strtoupper(substr($review->reviewer_name,0,1))); ?></span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-semibold"><?php echo e(e($review->reviewer_name)); ?></p>
                            <div class="flex text-warning text-sm"><?php for($i=1;$i<=5;$i++): ?><?php echo e($i<=$review->rating?'★':'☆'); ?><?php endfor; ?></div>
                            <span class="text-xs text-base-content/40"><?php echo e($review->created_at->diffForHumans()); ?></span>
                        </div>
                        <p class="text-sm text-base-content/80 mt-1"><?php echo e(e($review->comment)); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\seapedia\resources\views/public/reviews.blade.php ENDPATH**/ ?>