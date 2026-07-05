<?php if($paginator->hasPages()): ?>
    <nav class="join">
        <?php if($paginator->onFirstPage()): ?>
            <button class="join-item btn btn-disabled btn-sm">«</button>
        <?php else: ?>
            <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="join-item btn btn-sm">«</a>
        <?php endif; ?>

        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_string($element)): ?>
                <button class="join-item btn btn-disabled btn-sm"><?php echo e($element); ?></button>
            <?php endif; ?>

            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <button class="join-item btn btn-warning btn-sm"><?php echo e($page); ?></button>
                    <?php else: ?>
                        <a href="<?php echo e($url); ?>" class="join-item btn btn-sm"><?php echo e($page); ?></a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($paginator->hasMorePages()): ?>
            <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="join-item btn btn-sm">»</a>
        <?php else: ?>
            <button class="join-item btn btn-disabled btn-sm">»</button>
        <?php endif; ?>
    </nav>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\seapedia\resources\views/vendor/pagination/tailwind.blade.php ENDPATH**/ ?>