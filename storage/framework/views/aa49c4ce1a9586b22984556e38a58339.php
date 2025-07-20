<!-- resources/views/layouts/guest.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $__env->yieldContent('title', 'Guest Page'); ?></title>
  <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-[#F9FAF9] text-gray-800">
  <?php echo $__env->yieldContent('content'); ?>
</body>
</html>
<?php /**PATH C:\tpku-finance-baru\resources\views/layouts/guest.blade.php ENDPATH**/ ?>