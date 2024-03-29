<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title>Klubwears | <?php echo $__env->yieldContent('title', ''); ?></title>

        <link href="<?php echo e(asset('app-assets/images/icons/klub.png')); ?>" rel="SHORTCUT ICON" />

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat%7CRoboto:300,400,700" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Styles -->
        <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('css/responsive.css')); ?>">

        <?php echo $__env->yieldContent('extra-css'); ?>
    </head>


<body class="<?php echo $__env->yieldContent('body-class', ''); ?>">
    

    <?php echo $__env->yieldContent('content'); ?>

    

    <?php echo $__env->yieldContent('extra-js'); ?>

</body>
</html>
<?php /**PATH C:\xampp1\htdocs\klubwear\resources\views/layout.blade.php ENDPATH**/ ?>