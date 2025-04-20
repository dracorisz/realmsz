<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Realmsz &centerdot; <?php echo $__env->yieldContent('title'); ?></title>

  <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('favicon/favicon.ico')); ?>" /><![endif]-->
  <meta name="msapplication-TileImage" content="<?php echo e(asset('favicon/android-chrome-512x512.png')); ?>" />
  <meta name="msapplication-TileColor" content="#ffffff" />
  <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon/favicon.svg')); ?>" />
  <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon/favicon.ico')); ?>" />
  <link rel="apple-touch-icon" href="<?php echo e(asset('favicon/apple-touch-icon.png')); ?>" />
  <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e(asset('favicon/apple-touch-icon-57x57.png')); ?>" />
  <link rel="apple-touch-icon" sizes="72x72" href="<?php echo e(asset('favicon/apple-touch-icon-72x72.png')); ?>" />
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('favicon/apple-touch-icon-76x76.png')); ?>" />
  <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e(asset('favicon/apple-touch-icon-114x114.png')); ?>" />
  <link rel="apple-touch-icon" sizes="120x120" href="<?php echo e(asset('favicon/apple-touch-icon-120x120.png')); ?>" />
  <link rel="apple-touch-icon" sizes="144x144" href="<?php echo e(asset('favicon/apple-touch-icon-144x144.png')); ?>" />
  <link rel="apple-touch-icon" sizes="152x152" href="<?php echo e(asset('favicon/apple-touch-icon-152x152.png')); ?>" />
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('favicon/apple-touch-icon-180x180.png')); ?>" />

  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('favicon/favicon-16x16.png')); ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('favicon/favicon-32x32.png')); ?>">
  <link rel="icon" type="image/png" sizes="96x96" href="<?php echo e(asset('favicon/favicon-96x96.png')); ?>" />
  <link rel="icon" type="image/png" sizes="192x192" href="<?php echo e(asset('favicon/android-chrome-192x192.png')); ?>">
  <link rel="icon" type="image/png" sizes="512x512" href="<?php echo e(asset('favicon/android-chrome-512x512.png')); ?>">
  <link rel="manifest" href="<?php echo e(asset('favicon/site.webmanifest')); ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

  <style>
    html {
      -webkit-text-size-adjust: 100%;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont;
      min-width: 100vw;
      max-width: 100vw;
      width: 100vw;
      min-height: 100vh;
      max-height: 100vh;
      height: 100vh;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: start;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      position: relative;
      background: #000;
      background-color: #000;
    }

    *,
    :after,
    :before {
      box-sizing: border-box;
      border: 0 solid #fff;
    }

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: -1;
      opacity: 0.1;
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      margin: auto;
    }

    div {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding-top: 10rem;
      gap: 10px;
    }

    a,
    h1 {
      color: #555;
      line-height: 1;
      margin: 0;
      text-decoration: none;
      font-weight: 700;
    }

    a {
      padding: 1rem 2rem;
      /* text-transform: uppercase; */
      border-radius: 5rem;
      color: #000;
      background-color: #555;
      background: #555;
      transition: background-color .3s linear;
    }

    a:hover {
      background-color: #00c2c5;
    }
    
    .large {
      font-size: 15vw;
      margin-top: 3vw;
    }
    
    .medium {
      font-weight: 900;
      font-size: 5vw;
    }
  </style>
</head>

<body>
  <img src="<?php echo e(asset('cover.jpg')); ?>" />
  <div>
    <a href="https://realmsz.com">Go Home</a>
    <h1 class="large"><?php echo $__env->yieldContent('code'); ?></h1>
    <h1 class="medium"><?php echo $__env->yieldContent('message'); ?></h1>
  </div>
</body>

</html><?php /**PATH C:\Apache24\htdocs\realmsz-dev\resources\views/errors/minimal.blade.php ENDPATH**/ ?>