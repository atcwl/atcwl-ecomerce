<?php $__env->startSection('page_title', __('voyager.generic.view').' '.$dataType->display_name_singular); ?>

<?php $__env->startSection('page_header'); ?>
    <h1 class="page-title">
        <i class="<?php echo e($dataType->icon); ?>"></i> <?php echo e(__('voyager.generic.viewing')); ?> <?php echo e(ucfirst($dataType->display_name_singular)); ?> &nbsp;

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit', $dataTypeContent)): ?>
        <a href="<?php echo e(route('voyager.'.$dataType->slug.'.edit', $dataTypeContent->getKey())); ?>" class="btn btn-info">
            <span class="glyphicon glyphicon-pencil"></span>&nbsp;
            <?php echo e(__('voyager.generic.edit')); ?>

        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('voyager.'.$dataType->slug.'.index')); ?>" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            <?php echo e(__('voyager.generic.return_to_list')); ?>

        </a>
    </h1>
    <?php echo $__env->make('voyager::multilingual.language-selector', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="panel-heading" style="border-bottom:0;">
                            <h3 class="panel-title">Products for this Order</h3>
                        </div>


                        <div class="panel-body" style="padding-top:0;">
                            <ul>
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li style="margin-bottom: 10px">
                                        <div>Product Id: <?php echo e($product->id); ?></div>
                                        <div>Product Name: <?php echo e($product->name); ?></div>
                                        <div>Product Price: <?php echo e($product->presentPrice()); ?></div>
                                        <div>Product Quantity: <?php echo e($product->pivot->quantity); ?></div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>

                    <!-- form start -->
                    <?php $__currentLoopData = $dataType->readRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $rowDetails = json_decode($row->details);
                         if($rowDetails === null){
                                $rowDetails=new stdClass();
                                $rowDetails->options=new stdClass();
                         }
                        ?>

                        <div class="panel-heading" style="border-bottom:0;">
                            <h3 class="panel-title"><?php echo e($row->display_name); ?></h3>
                        </div>

                        <div class="panel-body" style="padding-top:0;">

                            <?php if($row->type == "image"): ?>
                                <img class="img-responsive"
                                     src="<?php echo e(filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Voyager::image($dataTypeContent->{$row->field})); ?>">
                            <?php elseif($row->type == 'multiple_images'): ?>
                                <?php if(json_decode($dataTypeContent->{$row->field})): ?>
                                    <?php $__currentLoopData = json_decode($dataTypeContent->{$row->field}); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img class="img-responsive"
                                             src="<?php echo e(filter_var($file, FILTER_VALIDATE_URL) ? $file : Voyager::image($file)); ?>">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <img class="img-responsive"
                                         src="<?php echo e(filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Voyager::image($dataTypeContent->{$row->field})); ?>">
                                <?php endif; ?>
                            <?php elseif($row->type == 'relationship'): ?>
                                 <?php echo $__env->make('voyager::formfields.relationship', ['view' => 'read', 'options' => $rowDetails], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif($row->type == 'select_dropdown' && property_exists($rowDetails, 'options') &&
                                    !empty($rowDetails->options->{$dataTypeContent->{$row->field}})
                            ): ?>

                                <?php echo $rowDetails->options->{$dataTypeContent->{$row->field}};?>
                            <?php elseif($row->type == 'select_dropdown' && $dataTypeContent->{$row->field . '_page_slug'}): ?>
                                <a href="<?php echo e($dataTypeContent->{$row->field . '_page_slug'}); ?>"><?php echo e($dataTypeContent->{$row->field}); ?></a>
                            <?php elseif($row->type == 'select_multiple'): ?>
                                <?php if(property_exists($rowDetails, 'relationship')): ?>

                                    <?php $__currentLoopData = json_decode($dataTypeContent->{$row->field}); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($item->{$row->field . '_page_slug'}): ?>
                                        <a href="<?php echo e($item->{$row->field . '_page_slug'}); ?>"><?php echo e($item->{$row->field}); ?></a><?php if(!$loop->last): ?>, <?php endif; ?>
                                        <?php else: ?>
                                        <?php echo e($item->{$row->field}); ?>

                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php elseif(property_exists($rowDetails, 'options')): ?>
                                    <?php $__currentLoopData = json_decode($dataTypeContent->{$row->field}); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <?php echo e($rowDetails->options->{$item} . (!$loop->last ? ', ' : '')); ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            <?php elseif($row->type == 'date' || $row->type == 'timestamp'): ?>
                                <?php echo e($rowDetails && property_exists($rowDetails, 'format') ? \Carbon\Carbon::parse($dataTypeContent->{$row->field})->formatLocalized($rowDetails->format) : $dataTypeContent->{$row->field}); ?>

                            <?php elseif($row->type == 'checkbox'): ?>
                                <?php if($rowDetails && property_exists($rowDetails, 'on') && property_exists($rowDetails, 'off')): ?>
                                    <?php if($dataTypeContent->{$row->field}): ?>
                                    <span class="label label-info"><?php echo e($rowDetails->on); ?></span>
                                    <?php else: ?>
                                    <span class="label label-primary"><?php echo e($rowDetails->off); ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                <?php echo e($dataTypeContent->{$row->field}); ?>

                                <?php endif; ?>
                            <?php elseif($row->type == 'color'): ?>
                                <span class="badge badge-lg" style="background-color: <?php echo e($dataTypeContent->{$row->field}); ?>"><?php echo e($dataTypeContent->{$row->field}); ?></span>
                            <?php elseif($row->type == 'coordinates'): ?>
                                <?php echo $__env->make('voyager::partials.coordinates', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php elseif($row->type == 'rich_text_box'): ?>
                                <?php echo $__env->make('voyager::multilingual.input-hidden-bread-read', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <p><?php echo $dataTypeContent->{$row->field}; ?></p>
                            <?php elseif($row->type == 'file'): ?>
                                <?php if(json_decode($dataTypeContent->{$row->field})): ?>
                                    <?php $__currentLoopData = json_decode($dataTypeContent->{$row->field}); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="/storage/<?php echo e($file->download_link or ''); ?>">
                                            <?php echo e($file->original_name or ''); ?>

                                        </a>
                                        <br/>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <a href="/storage/<?php echo e($dataTypeContent->{$row->field}); ?>">
                                        Download
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php echo $__env->make('voyager::multilingual.input-hidden-bread-read', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php if($row->display_name == 'Total' || $row->display_name == 'Subtotal' || $row->display_name == 'Tax' || $row->display_name == 'Discount'): ?>
                                    <p>ksh<?php echo e($dataTypeContent->{$row->field} / 100); ?></p>
                                <?php else: ?>
                                    <p><?php echo e($dataTypeContent->{$row->field}); ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div><!-- panel-body -->
                        <?php if(!$loop->last): ?>
                            <hr style="margin:0;">
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
    <?php if($isModelTranslatable): ?>
    <script>
        $(document).ready(function () {
            $('.side-body').multilingual();
        });
    </script>
    <script src="<?php echo e(voyager_asset('js/multilingual.js')); ?>"></script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('voyager::master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\klubwear\resources\views/vendor/voyager/orders/read.blade.php ENDPATH**/ ?>