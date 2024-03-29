
<?php $__env->startSection('title', 'Cart'); ?>
<?php $__env->startSection('content'); ?>
<div class="cart-section container">
	<div>
		<?php if(session()->has('success_message')): ?>
			<div class="alert alert-success">
				<?php echo e(session()->get('success_message')); ?>

			</div>
		<?php endif; ?>

		<?php if(count($errors) > 0): ?>
			<div class="alert alert-danger">
				<ul>
					<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($error); ?></li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if(Cart::count() > 0 ): ?>



		<h2><?php echo e(Cart::count()); ?> item(s) in Shopping Cart</h2>
		<div class="cart-table">
			<?php $__currentLoopData = Cart::content(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="cart-table-row">
				<div class="cart-table-row-left">
					<a href="<?php echo e(route('shop.show', $item->model->slug)); ?>"><img src="<?php echo e(productImage($item->model->image)); ?>" alt="item" class="cart-table-img"></a>
					<div class="cart-item-details">
						<div class="cart-table-item"><a
								href="<?php echo e(route('shop.show', $item->model->slug)); ?>"><?php echo e($item->model->name); ?></a></div>
						<div class="cart-table-description"><?php echo e($item->model->details); ?></div>

					</div>
				</div>
				<div class="cart-table-row-right">
					<div class="cart-table-actions">
						<form action="<?php echo e(route('cart.destroy', $item->rowId)); ?>" method="POST">
							<?php echo e(csrf_field()); ?>

							<?php echo e(method_field('DELETE')); ?>

							<button type="submit" class="remove">
								<i class="flaticon-waste-can"></i>
								<span class="remove__text"></span>
							</button>
						</form>

					</div>
					<div>
						<select class="quantity" data-id="<?php echo e($item->rowId); ?>" data-productQuantity="<?php echo e($item->model->quantity); ?>">
							<?php for($i = 1; $i < 5 + 1 ; $i++): ?>
								<option <?php echo e($item->qty == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div><?php echo e(presentPrice($item->subtotal())); ?></div>
				</div>
			</div> <!-- end cart-table-row -->
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div> <!-- end cart-table -->
		<?php if(! session()->has('coupon')): ?>

		<a href="#" class="have-code">Have a Code?</a>

		<div class="have-code-container">
			<form action="<?php echo e(route('coupon.store')); ?>" method="POST">
				<?php echo e(csrf_field()); ?>

				<input type="text" name="coupon_code" id="coupon_code">
				<button type="submit" class="button button-plain">Apply</button>
			</form>
		</div> <!-- end have-code-container -->
		<?php endif; ?>

		
		<div class="cart-totals">
			<div class="cart-totals-left">
				Shipping is free within Nairobi. Grab as more Items as you can.
			</div>

			<div class="cart-totals-right">
				<div>
					Subtotal <br>
					<?php if(session()->has('coupon')): ?>
						Code (<?php echo e(session()->get('coupon')['name']); ?>)
						<form action="<?php echo e(route('coupon.destroy')); ?>" method="POST" style="display:block">
							<?php echo e(csrf_field()); ?>

							<?php echo e(method_field('delete')); ?>

							<button type="submit" style="font-size:14px;">Remove</button>
						</form>
						<hr>
						New Subtotal <br>
					<?php endif; ?>
					Tax (<?php echo e(config('cart.tax')); ?>%)<br>
					<span class="cart-totals-total">Total</span>
				</div>
				<div class="cart-totals-subtotal">
					<?php echo e(presentPrice(Cart::subtotal())); ?> <br>
					<?php if(session()->has('coupon')): ?>
					-<?php echo e(presentPrice($discount)); ?> <br>&nbsp;<br>
						<hr><br>
					<?php echo e(presentPrice($newSubtotal)); ?> <br>
					<?php endif; ?>
					<?php echo e(presentPrice($newTax)); ?> <br>
					<span class="cart-totals-total"><?php echo e(presentPrice($newTotal)); ?></span>
				</div>
			</div>
		</div> <!-- end cart-totals -->

		<div class="cart-buttons">
			<a href="<?php echo e(route('shop.index')); ?>" class="button">Continue Shopping</a>
			<a href="<?php echo e(route('checkout.index')); ?>" class="button-primary">Proceed to Checkout</a>
		</div>
		<?php else: ?>
		<h2>
			<center>No items in the cart ! !</center>
		</h2>
		<div class="spacer"></div>
		<section class="section-last-posts">
			<div class="text-center mt-lg-4">
				<a href="<?php echo e(route('shop.index')); ?>" class="btn btn-outline-primary btn-lg">Start Shopping</a>
			</div>
		</section>
		<?php endif; ?>
	</div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('extra-js'); ?>
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <script>
        (function(){
            const classname = document.querySelectorAll('.quantity')

            Array.from(classname).forEach(function(element) {
                element.addEventListener('change', function() {
                    const id = element.getAttribute('data-id')
                    const productQuantity = element.getAttribute('data-productQuantity')

                    axios.patch(`/cart/${id}`, {
                        quantity: this.value,
                        productQuantity: productQuantity
                    })
                    .then(function (response) {
                        // console.log(response);
                        window.location.href = '<?php echo e(route('cart.index')); ?>'
                    })
                    .catch(function (error) {
                        // console.log(error);
                        window.location.href = '<?php echo e(route('cart.index')); ?>'
                    });
                })
            })
        })();
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('shopping.layouts.new', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\klubwear\resources\views/shopping/pages/cart.blade.php ENDPATH**/ ?>