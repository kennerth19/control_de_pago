<title>Home</title>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><?php echo e(__('Dashboard')); ?></div>

                <div class="card-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <a class="back" href="<?php echo e(route('control')); ?>">Control de pagos</a>
                    <br>
                    <a class="back" href="<?php echo e(route('lista.fibreros')); ?>">Lista de instalaciones de fibra</a>
                    <br>
                    <a class="back" href="<?php echo e(route('lista.anteneros')); ?>">Lista de instalaciones de antena</a>
                </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\control_de_pago\resources\views/home.blade.php ENDPATH**/ ?>