<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="site-name" content="{{ gs('site_name') }}">
    <meta name="google-site-verification" content="E3gka7qlbFBAVf2PFRhspn0KvnGd7G58tppwj8DHjxY" />
    <meta name="description" content="{{ gs('site_name') }} is a cryptocurrency exchange platform where you can trade Bitcoin, Ethereum and other digital assets with professional tools and 24/7 support.">
    <link rel="icon" type="image/png" href="{{ siteFavicon() }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo_icon/logo_mark.png') }}">
    <meta name="theme-color" content="#06070a">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/js/app.js')
    @inertiaHead
</head>
<body class="bg-ink-950 text-zinc-100 antialiased">
    @inertia
</body>
</html>
