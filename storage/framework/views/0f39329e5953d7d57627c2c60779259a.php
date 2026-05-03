<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'SanCo'); ?></title>

    <!-- Load Tailwind via Vite -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- Livewire Styles -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="font-sans antialiased text-gray-100 bg-[#18181b]">
    
    <?php echo e($slot); ?>


    <!-- Livewire Scripts -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


    <!-- Global Notifications -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            <?php if(session('success')): ?>
                window.notyf.success("<?php echo e(session('success')); ?>");
            <?php endif; ?>

            <?php if(session('error')): ?>
                window.notyf.error("<?php echo e(session('error')); ?>");
            <?php endif; ?>
        });
    </script>
</body>
</html>
<?php /**PATH C:\Users\johan\Desktop\Laravel\SanCo\resources\views/layouts/auth.blade.php ENDPATH**/ ?>