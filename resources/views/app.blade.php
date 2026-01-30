<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="X-UA-Compatible" content="IE=Edge"> 

  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-EKWP0G51QH"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-EKWP0G51QH');
  </script>

  <title inertia>Realmsz</title>

  <meta name="author" content="Realmsz Team" />
  <meta name="robots" content="index, follow" />
  <meta name="theme-color" content="#00449f" />
  <meta name="description" content="Realmsz combines 10 essential modules in one place: Focus, Plans, Profile, Network, Milai, Studio, Finance, Crypto, Websites, and Storage. Streamline your workflow, create content, connect with your community, and manage your digital assets. Get started by creating an account and exploring each module!" />
  <meta name="robots" content="index, follow" />
  <meta name="keywords" content="ai, generation, productivity, focus, plans, profile, network, milai, studio, finance, crypto, websites, storage" />

  <link rel="image_src" href="https://dracoscopia.com/images/ai/3.png" />
  <link rel="canonical" href="https://realmsz.com" />

  <meta property="og:title" content="Realmsz" />
  <meta property="og:description" content="Realmsz combines 10 essential modules in one place: Focus, Plans, Profile, Network, Milai, Studio, Finance, Crypto, Websites, and Storage. Streamline your workflow, create content, connect with your community, and manage your digital assets. Get started by creating an account and exploring each module!" />
  <meta property="og:locale" content="en_US" />
  <meta property="og:locale:alternate" content="es_GB" />
  <meta property="og:site_name" content="Realmsz" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://realmsz.com" />
  <meta property="og:image" content="https://dracoscopia.com/images/ai/3.png" />
  <meta property="og:image:secure_url" content="https://dracoscopia.com/images/ai/3.png" />
  <meta property="og:image:type" content="image/jpg" />
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="630" />

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Realmsz" />
  <meta name="twitter:description" content="Realmsz combines 10 essential modules in one place: Focus, Plans, Profile, Network, Milai, Studio, Finance, Crypto, Websites, and Storage. Streamline your workflow, create content, connect with your community, and manage your digital assets. Get started by creating an account and exploring each module!" />
  <meta name="twitter:image" content="https://dracoscopia.com/images/ai/3.png" />
  <meta name="twitter:site" content="@dracoscopia" />
  
  <!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon/favicon.ico') }}" /><![endif]-->
  <meta name="msapplication-TileImage" content="{{ asset('favicon/android-chrome-512x512.png') }}" />
  <meta name="msapplication-TileColor" content="#ffffff" />
  <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}" />
  <link rel="icon" type="image/x-icon" href="{{ asset('favicon/favicon.ico') }}" />
  <link rel="apple-touch-icon" href="{{ asset('favicon/apple-touch-icon.png') }}" />
  <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-touch-icon-57x57.png') }}" />
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-touch-icon-72x72.png') }}" />
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-touch-icon-76x76.png') }}" />
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-touch-icon-114x114.png') }}" />
  <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-touch-icon-120x120.png') }}" />
  <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-touch-icon-144x144.png') }}" />
  <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-touch-icon-152x152.png') }}" />
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon-180x180.png') }}" />
  
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}" />
  <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-chrome-192x192.png') }}">
  <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon/android-chrome-512x512.png') }}">
  <link rel="icon" href="{{ asset('favicon/android-chrome-512x512.png') }}">
  <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('prism/prism.css') }}">
  <script src="{{ asset('prism/prism.js') }}"></script>

  @routes
  @vite(['resources/js/app.js', 'resources/css/app.css', "resources/js/Pages/{$page['component']}.vue"])
  @inertiaHead

  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Realmsz",
      "headline": "Realmsz",
      "description": "Realmsz combines 10 essential modules in one place: Focus, Plans, Profile, Network, Milai, Studio, Finance, Crypto, Websites, and Storage. Streamline your workflow, create content, connect with your community, and manage your digital assets. Get started by creating an account and exploring each module!",
      "image": "https://dracoscopia.com/images/ai/3.png",
    }
  </script>
  <!-- "url": "https://realmsz.com" -->
</head>

<body class="font-primary antialiased relative tracking-wide selection:text-black selection:bg-accent scroll-smooth overflow-x-hidden h-screen">
  @inertia
</body>

</html>