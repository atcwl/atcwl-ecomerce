<div class="might-like-section">
    <div class="container">
        <h2>You might also like...</h2>
        <div class="might-like-grid">
            <?php $__currentLoopData = $mightAlsoLike; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('shop.show', $product->slug)); ?>" class="might-like-product">
                    <img src="<?php echo e(productImage($product->image)); ?>" alt="product">
                    <div class="might-like-product-name"><?php echo e($product->name); ?></div>
                    <div class="might-like-product-price"><?php echo e($product->presentPrice()); ?></div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    </div>
</div>
<?php /**PATH C:\xampp1\htdocs\klubwear\resources\views/partials/might-like.blade.php ENDPATH**/ ?>