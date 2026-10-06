<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta name="google-site-verification" content="E3gka7qlbFBAVf2PFRhspn0KvnGd7G58tppwj8DHjxY" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ gs()->siteName(__($pageTitle)) }}</title>
    <meta name="description" content="{{ gs()->siteName(__($pageTitle)) }} is a cryptocurrency exchange platform, where you can trade Bitcoin, Ethereum, Litecoin, and other cryptocurrencies with advanced security, professional tools, and 24/7 support for traders worldwide.">
    <meta name="keywords" content="cryptocurrency trading, bitcoin trading, ethereum trading, crypto exchange, digital assets, {{ gs()->siteName(__($pageTitle)) }}">
    <link rel="canonical" >
    <meta name="last-modified" content="2025-01-01">

    <!-- Social Media Tags -->
    <meta property="og:title" content="{{ gs()->siteName(__($pageTitle)) }} - Professional Cryptocurrency Trading Platform">
    <meta property="og:description" content="{{ gs()->siteName(__($pageTitle)) }} is a cryptocurrency exchange platform, where you can trade Bitcoin, Ethereum, Litecoin, and other cryptocurrencies.">
    <meta property="og:type" content="website">
    <meta property="og:url" >

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">



    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #09090b;
            color: white;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Header Styles */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            background: rgba(9, 9, 11, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(39, 39, 42, 0.5);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 4rem;
            padding: 0 1rem;
        }

        .logo-nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: white;
        }

        .logo img {
            height: 2.25rem;
            width: auto;
            max-width: 220px;
            -o-object-fit: contain;
            object-fit: contain;
        }

        .nav {
            display: none;
        }

        @media (min-width: 1024px) {
            .nav {
                display: flex;
                gap: 1.25rem;
            }
        }

        .nav a {
            color: white;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 1rem 0;
            transition: color 0.3s;
        }

        .nav a:hover {
            color: #60a5fa;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .flag-btn {
            display: none;
            padding: 0.5rem;
            border-radius: 9999px;
            background: transparent;
            border: none;
            color: #a1a1aa;
            cursor: pointer;
            transition: all 0.3s;
        }

        @media (min-width: 768px) {
            .flag-btn {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
        }

        .flag-btn:hover {
            color: #f4f4f5;
            background: #27272a;
        }

        .flag-btn img {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 9999px;
            -o-object-fit: cover;
            object-fit: cover;
        }

        .auth-buttons {
            display: none;
            gap: 0.5rem;
        }

        @media (min-width: 768px) {
            .auth-buttons {
                display: flex;
            }
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            white-space: nowrap;
        }

        .btn-secondary {
            background: transparent;
            color: white;
        }

        .btn-secondary:hover {
            background: #27272a;
            color: #f4f4f5;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .mobile-menu-btn {
            display: block;
            padding: 0.5rem;
            border-radius: 0.375rem;
            background: transparent;
            border: none;
            color: white;
            cursor: pointer;
        }

        @media (min-width: 768px) {
            .mobile-menu-btn {
                display: none;
            }
        }

        .mobile-menu-btn:hover {
            background: #27272a;
        }

        /* Mobile Menu */
        .mobile-menu {
            position: fixed;
            inset: 0;
            z-index: 40;
            background: rgba(9, 9, 11, 0.95);
            backdrop-filter: blur(20px);
            display: none;
        }

        .mobile-menu.active {
            display: block;
        }

        .mobile-menu-content {
            padding-top: 5rem;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .mobile-nav {
            margin-bottom: 2rem;
        }

        .mobile-nav a {
            display: block;
            padding: 0.75rem 0;
            font-size: 1.125rem;
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .mobile-nav a:hover {
            color: #60a5fa;
        }

        .mobile-auth {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .mobile-auth .btn {
            width: 100%;
            padding: 0.75rem;
            text-align: center;
        }

        .mobile-auth .btn-secondary {
            border: 1px solid #3f3f46;
        }

        /* Hero Section */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-gradient {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, rgba(37, 99, 235, 0.2), rgba(147, 51, 234, 0.2), rgba(79, 70, 229, 0.2));
        }

        .hero-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .hero-shape {
            position: absolute;
            border-radius: 9999px;
            filter: blur(48px);
            animation: pulse 3s ease-in-out infinite;
        }

        .hero-shape-1 {
            top: 25%;
            left: 25%;
            width: 24rem;
            height: 24rem;
            background: linear-gradient(to bottom right, rgba(59, 130, 246, 0.3), rgba(34, 197, 94, 0.3));
        }

        .hero-shape-2 {
            bottom: 25%;
            right: 25%;
            width: 24rem;
            height: 24rem;
            background: linear-gradient(to bottom right, rgba(147, 51, 234, 0.3), rgba(236, 72, 153, 0.3));
            animation-delay: 1s;
        }

        .hero-shape-3 {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 24rem;
            height: 24rem;
            background: linear-gradient(to bottom right, rgba(79, 70, 229, 0.2), rgba(59, 130, 246, 0.2));
            animation-delay: 2s;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .hero-content {
            position: relative;
            z-index: 10;
            padding-top: 5rem;
        }

        .hero-grid {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            justify-content: space-between;
        }

        @media (min-width: 1024px) {
            .hero-grid {
                flex-direction: row;
                gap: 3rem;
            }
        }

        .hero-text {
            text-align: center;
            width: 100%;
        }

        @media (min-width: 1024px) {
            .hero-text {
                width: 60%;
                text-align: left;
            }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(to right, rgba(30, 58, 138, 0.5), rgba(88, 28, 135, 0.5));
            border: 1px solid rgba(29, 78, 216, 0.5);
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #93c5fd;
            margin-bottom: 2rem;
        }

        .hero-title {
            font-size: 2.25rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }

        @media (min-width: 640px) {
            .hero-title {
                font-size: 3rem;
            }
        }

        @media (min-width: 768px) {
            .hero-title {
                font-size: 3.75rem;
            }
        }

        @media (min-width: 1024px) {
            .hero-title {
                font-size: 4.5rem;
            }
        }

        .hero-title-line {
            display: block;
            margin-bottom: -0.25rem;
        }

        @media (min-width: 768px) {
            .hero-title-line {
                margin-bottom: -0.5rem;
            }
        }

        .hero-title-gradient {
            background: linear-gradient(to right, #2563eb, #7c3aed, #4f46e5);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .hero-description {
            font-size: 1.125rem;
            margin-bottom: 2rem;
            max-width: 32rem;
            line-height: 1.6;
            margin-left: auto;
            margin-right: auto;
            color: #d4d4d8;
        }

        @media (min-width: 768px) {
            .hero-description {
                font-size: 1.25rem;
            }
        }

        @media (min-width: 1024px) {
            .hero-description {
                font-size: 1.5rem;
                margin-left: 0;
                margin-right: 0;
            }
        }

        .hero-cta {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 3rem;
            justify-content: center;
        }

        @media (min-width: 640px) {
            .hero-cta {
                flex-direction: row;
            }
        }

        @media (min-width: 1024px) {
            .hero-cta {
                justify-content: flex-start;
            }
        }

        .hero-cta-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: linear-gradient(to right, #2563eb, #7c3aed);
            border-radius: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            color: white;
            text-decoration: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transform: scale(1);
            width: -moz-fit-content;
            width: fit-content;
            margin: 0 auto;
        }

        @media (min-width: 640px) {
            .hero-cta-btn {
                margin: 0;
            }
        }

        @media (min-width: 768px) {
            .hero-cta-btn {
                padding: 1rem 2rem;
                border-radius: 1.5rem;
            }
        }

        .hero-cta-btn:hover {
            background: linear-gradient(to right, #1d4ed8, #6d28d9);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(1.05);
        }

        .hero-features {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            justify-content: center;
        }

        @media (min-width: 1024px) {
            .hero-features {
                justify-content: flex-start;
            }
        }

        .hero-feature {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #d4d4d8;
        }

        .hero-trading {
            width: 100%;
        }

        @media (min-width: 1024px) {
            .hero-trading {
                width: 40%;
            }
        }

        .trading-card {
            position: relative;
            backdrop-filter: blur(20px);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(63, 63, 70, 0.5);
            background: rgba(39, 39, 42, 0.5);
        }

        @media (min-width: 768px) {
            .trading-card {
                padding: 2rem;
            }
        }

        @media (min-width: 1024px) {
            .trading-card {
                padding: 2rem;
            }
        }

        .trading-card::before {
            content: '';
            position: absolute;
            top: -1rem;
            right: -1rem;
            width: 6rem;
            height: 6rem;
            background: linear-gradient(to bottom right, rgba(59, 130, 246, 0.2), rgba(147, 51, 234, 0.2));
            border-radius: 9999px;
            filter: blur(20px);
        }

        .trading-card::after {
            content: '';
            position: absolute;
            bottom: -1rem;
            left: -1rem;
            width: 8rem;
            height: 8rem;
            background: linear-gradient(to bottom right, rgba(147, 51, 234, 0.2), rgba(236, 72, 153, 0.2));
            border-radius: 9999px;
            filter: blur(20px);
        }

        .trading-content {
            position: relative;
            z-index: 10;
        }

        .trading-header {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #9ca3af;
            padding: 0 0.5rem;
            margin-bottom: 1rem;
        }

        @media (min-width: 768px) {
            .trading-header {
                font-size: 0.875rem;
            }
        }

        .trading-header-price {
            text-align: center;
            display: flex;
            gap: 0.5rem;
        }

        .trading-header-change {
            text-align: right;
        }

        .trading-list {
            margin-bottom: 1.5rem;
        }

        .trading-item {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            padding: 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
            cursor: pointer;
            background: rgba(39, 39, 42, 0.3);
            border: 1px solid rgba(63, 63, 70, 0.3);
            margin-bottom: 1rem;
        }

        @media (min-width: 768px) {
            .trading-item {
                padding: 1rem;
            }
        }

        .trading-item:hover {
            transform: scale(1.02);
            background: rgba(63, 63, 70, 0.5);
            border-color: rgba(82, 82, 91, 0.5);
        }

        .trading-asset {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .trading-icon {
            min-width: 2rem;
            min-height: 2rem;
            width: 2rem;
            height: 2rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 2px solid #3f3f46;
            flex-shrink: 0;
            background: #27272a;
        }

        @media (min-width: 768px) {
            .trading-icon {
                min-width: 3rem;
                min-height: 3rem;
                width: 3rem;
                height: 3rem;
            }
        }

        .trading-icon img {
            width: 1.5rem;
            height: 1.5rem;
            -o-object-fit: cover;
            object-fit: cover;
        }

        @media (min-width: 768px) {
            .trading-icon img {
                width: 2rem;
                height: 2rem;
            }
        }

        .trading-symbol {
            font-weight: 600;
            font-size: 0.75rem;
            transition: color 0.3s;
        }

        @media (min-width: 768px) {
            .trading-symbol {
                font-size: 0.875rem;
            }
        }

        .trading-item:hover .trading-symbol {
            color: #60a5fa;
        }

        .trading-name {
            font-size: 0.625rem;
            color: #a1a1aa;
        }

        @media (min-width: 768px) {
            .trading-name {
                font-size: 0.75rem;
            }
        }

        .trading-price-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        .trading-price {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            font-size: 0.75rem;
        }

        @media (min-width: 768px) {
            .trading-price {
                font-size: 0.875rem;
            }
        }

        .trading-cap {
            font-weight: 500;
            font-size: 0.625rem;
            color: #9ca3af;
        }

        @media (min-width: 768px) {
            .trading-cap {
                font-size: 0.75rem;
            }
        }

        .trading-change-col {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .trading-change {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #f87171;
            background: rgba(153, 27, 27, 0.3);
        }

        .trading-change.positive {
            color: #22c55e;
            background: rgba(34, 197, 94, 0.3);
        }

        @media (min-width: 768px) {
            .trading-change {
                font-size: 0.875rem;
            }
        }

        .trading-footer {
            text-align: center;
        }

        .trading-footer a {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #60a5fa;
            font-weight: 500;
            transition: color 0.3s;
            text-decoration: none;
            font-size: 0.875rem;
        }

        @media (min-width: 768px) {
            .trading-footer a {
                font-size: 1rem;
            }
        }

        .trading-footer a:hover {
            color: #93c5fd;
        }

        /* Ticker Section */
        .ticker {
            padding: 1.5rem 0;
            border-top: 1px solid rgba(63, 63, 70, 0.5);
            border-bottom: 1px solid rgba(63, 63, 70, 0.5);
            backdrop-filter: blur(8px);
            background: rgba(39, 39, 42, 0.5);
        }

        .ticker-container {
            overflow: hidden;
        }

        .ticker-content {
            display: flex;
            white-space: nowrap;
            animation: scroll 30s linear infinite;
        }

        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .ticker-item {
            display: inline-flex;
            align-items: center;
            margin: 0 1.5rem;
            color: #d1d5db;
        }

        .ticker-symbol {
            font-weight: 500;
        }

        .ticker-price {
            margin: 0 0.5rem;
            font-family: 'Courier New', monospace;
        }

        .ticker-change {
            color: #ef4444;
        }

        .ticker-change.positive {
            color: #22c55e;
        }

        /* Loading Animation */
        .price-updating {
            animation: priceUpdate 0.3s ease-in-out;
        }

        @keyframes priceUpdate {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Features Section */
        .features {
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .features {
                padding: 6rem 0;
            }
        }

        @media (min-width: 1024px) {
            .features {
                padding: 8rem 0;
            }
        }

        .features::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent, rgba(30, 58, 138, 0.1), transparent);
        }

        .features-content {
            position: relative;
            z-index: 10;
        }

        .features-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .features-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(to right, rgba(30, 58, 138, 0.5), rgba(88, 28, 135, 0.5));
            border: 1px solid rgba(29, 78, 216, 0.5);
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #93c5fd;
            margin-bottom: 2rem;
        }

        .features-title {
            font-size: 1.875rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 768px) {
            .features-title {
                font-size: 2.25rem;
            }
        }

        @media (min-width: 1024px) {
            .features-title {
                font-size: 3rem;
            }
        }

        .features-title-gradient {
            background: linear-gradient(to right, #2563eb, #7c3aed, #4f46e5);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .features-description {
            font-size: 1.125rem;
            max-width: 48rem;
            margin: 0 auto;
            line-height: 1.6;
            color: #d4d4d8;
        }

        @media (min-width: 768px) {
            .features-description {
                font-size: 1.25rem;
            }
        }

        .features-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 640px) {
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .features-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .feature-card {
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(8px);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid rgba(63, 63, 70, 0.5);
            transition: all 0.5s;
            cursor: pointer;
            background: rgba(39, 39, 42, 0.3);
        }

        .feature-card:hover {
            transform: scale(1.05);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: -1rem;
            right: -1rem;
            width: 6rem;
            height: 6rem;
            opacity: 0.2;
            border-radius: 9999px;
            filter: blur(20px);
            transition: opacity 0.5s;
        }

        .feature-card:hover::before {
            opacity: 0.3;
        }

        .feature-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .feature-icon.yellow {
            background: linear-gradient(to right, #facc15, #f97316);
        }

        .feature-icon.green {
            background: linear-gradient(to right, #22c55e, #10b981);
        }

        .feature-icon.blue {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
        }

        .feature-icon.purple {
            background: linear-gradient(to right, #a855f7, #ec4899);
        }

        .feature-icon.red {
            background: linear-gradient(to right, #ef4444, #f43f5e);
        }

        .feature-icon.indigo {
            background: linear-gradient(to right, #6366f1, #3b82f6);
        }

        .feature-icon i {
            color: white;
            font-size: 1.5rem;
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 1rem;
            transition: color 0.3s;
        }

        .feature-card:hover .feature-title {
            color: #60a5fa;
        }

        .feature-description {
            line-height: 1.6;
            color: #d4d4d8;
        }

        /* Platform Section */
        .platform {
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
            background: rgba(39, 39, 42, 0.3);
        }

        @media (min-width: 768px) {
            .platform {
                padding: 6rem 0;
            }
        }

        @media (min-width: 1024px) {
            .platform {
                padding: 8rem 0;
            }
        }

        .platform-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 4rem;
            align-items: center;
        }

        @media (min-width: 1024px) {
            .platform-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .platform-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(to right, rgba(30, 58, 138, 0.5), rgba(88, 28, 135, 0.5));
            border: 1px solid rgba(29, 78, 216, 0.5);
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #93c5fd;
            margin-bottom: 2rem;
        }

        .platform-title {
            font-size: 2.25rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 768px) {
            .platform-title {
                font-size: 3rem;
            }
        }

        .platform-title-gradient {
            background: linear-gradient(to right, #2563eb, #7c3aed, #4f46e5);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .platform-description {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            line-height: 1.6;
            color: #d4d4d8;
        }

        .platform-features {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 640px) {
            .platform-features {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .platform-feature {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 0.75rem;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(63, 63, 70, 0.5);
            background: rgba(39, 39, 42, 0.3);
        }

        .platform-feature-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.75rem;
            background: linear-gradient(to right, #3b82f6, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .platform-feature-icon i {
            color: white;
            font-size: 1.25rem;
        }

        .platform-feature-title {
            font-weight: 600;
        }

        .platform-feature-subtitle {
            font-size: 0.875rem;
            color: #a1a1aa;
        }

        .platform-card {
            position: relative;
            backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            padding: 2rem;
            border: 1px solid rgba(63, 63, 70, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            background: rgba(39, 39, 42, 0.5);
        }

        .platform-card::before {
            content: '';
            position: absolute;
            top: -1rem;
            right: -1rem;
            width: 8rem;
            height: 8rem;
            background: linear-gradient(to bottom right, rgba(59, 130, 246, 0.2), rgba(147, 51, 234, 0.2));
            border-radius: 9999px;
            filter: blur(32px);
        }

        .platform-card-content {
            position: relative;
            z-index: 10;
        }

        .platform-card-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .platform-list {
            list-style: none;
        }

        .platform-list-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .platform-list-item i {
            color: #22c55e;
            font-size: 1.125rem;
        }

        .platform-list-item span {
            color: #d4d4d8;
        }

        /* Steps Section */
        .steps {
            padding: 4rem 0;
        }

        @media (min-width: 768px) {
            .steps {
                padding: 6rem 0;
            }
        }

        @media (min-width: 1024px) {
            .steps {
                padding: 8rem 0;
            }
        }

        .steps-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .steps-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(to right, rgba(30, 58, 138, 0.5), rgba(88, 28, 135, 0.5));
            border: 1px solid rgba(29, 78, 216, 0.5);
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #93c5fd;
            margin-bottom: 2rem;
        }

        .steps-title {
            font-size: 2.25rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        @media (min-width: 768px) {
            .steps-title {
                font-size: 3rem;
            }
        }

        .steps-title-gradient {
            background: linear-gradient(to right, #2563eb, #7c3aed, #4f46e5);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        @media (min-width: 768px) {
            .steps-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .step-card {
            position: relative;
            backdrop-filter: blur(8px);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid rgba(63, 63, 70, 0.5);
            transition: all 0.5s;
            background: rgba(39, 39, 42, 0.3);
        }

        .step-card:hover {
            transform: scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-color: rgba(82, 82, 91, 0.7);
        }

        .step-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 1rem;
            overflow: hidden;
        }

        .step-card::after {
            content: '';
            position: absolute;
            top: -1rem;
            right: -1rem;
            width: 6rem;
            height: 6rem;
            background: linear-gradient(to bottom right, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
            border-radius: 9999px;
            filter: blur(20px);
            transition: all 0.5s;
        }

        .step-card:hover::after {
            background: linear-gradient(to bottom right, rgba(59, 130, 246, 0.2), rgba(147, 51, 234, 0.2));
        }

        .step-content {
            position: relative;
            z-index: 10;
        }

        .step-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .step-icon {
            width: 4rem;
            height: 4rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s;
        }

        .step-card:hover .step-icon {
            transform: scale(1.1);
        }

        .step-icon.blue {
            background: linear-gradient(to right, #3b82f6, #06b6d4);
        }

        .step-icon.purple {
            background: linear-gradient(to right, #7c3aed, #ec4899);
        }

        .step-icon.orange {
            background: linear-gradient(to right, #f97316, #ef4444);
        }

        .step-icon i {
            font-size: 1.5rem;
        }

        .step-number {
            font-size: 2.25rem;
            font-weight: bold;
            color: #3f3f46;
            transition: all 0.3s;
        }

        .step-card:hover .step-number {
            color: #52525b;
        }

        .step-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 1rem;
            transition: color 0.3s;
        }

        .step-card:hover .step-title {
            color: #60a5fa;
        }

        .step-description {
            line-height: 1.6;
            transition: color 0.3s;
            color: #d4d4d8;
        }

        .step-card:hover .step-description {
            color: #e4e4e7;
        }

        /* Mobile App Section */
        .mobile-app {
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
        }

        @media (min-width: 768px) {
            .mobile-app {
                padding: 6rem 0;
            }
        }

        @media (min-width: 1024px) {
            .mobile-app {
                padding: 8rem 0;
            }
        }

        .mobile-app-bg {
            position: absolute;
            inset: 0;
        }

        .mobile-app-shape-1 {
            position: absolute;
            top: 0;
            left: 25%;
            width: 24rem;
            height: 24rem;
            background: linear-gradient(to bottom right, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
            border-radius: 9999px;
            filter: blur(48px);
        }

        .mobile-app-shape-2 {
            position: absolute;
            bottom: 0;
            right: 25%;
            width: 24rem;
            height: 24rem;
            background: linear-gradient(to bottom right, rgba(147, 51, 234, 0.1), rgba(236, 72, 153, 0.1));
            border-radius: 9999px;
            filter: blur(48px);
        }

        .mobile-app-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, rgba(39, 39, 42, 0.5), transparent, rgba(39, 39, 42, 0.5));
        }

        .mobile-app-content {
            position: relative;
            z-index: 10;
        }

        .mobile-app-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 5rem;
            align-items: center;
        }

        @media (min-width: 1024px) {
            .mobile-app-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .mobile-app-text {
            order: 2;
        }

        @media (min-width: 1024px) {
            .mobile-app-text {
                order: 1;
            }
        }

        .mobile-app-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(to right, rgba(30, 58, 138, 0.5), rgba(88, 28, 135, 0.5));
            border: 1px solid rgba(29, 78, 216, 0.5);
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #93c5fd;
            margin-bottom: 2rem;
        }

        .mobile-app-title {
            font-size: 2.25rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }

        @media (min-width: 768px) {
            .mobile-app-title {
                font-size: 3rem;
            }
        }

        @media (min-width: 1024px) {
            .mobile-app-title {
                font-size: 3.75rem;
            }
        }

        .mobile-app-title-line {
            display: block;
            background: linear-gradient(to right, #2563eb, #7c3aed, #4f46e5);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }

        .mobile-app-description {
            font-size: 1.125rem;
            margin-bottom: 2rem;
            line-height: 1.6;
            color: #d4d4d8;
        }

        @media (min-width: 768px) {
            .mobile-app-description {
                font-size: 1.25rem;
            }
        }

        .mobile-app-features {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        @media (min-width: 640px) {
            .mobile-app-features {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .mobile-app-feature {
            text-align: center;
        }

        .mobile-app-feature-icon {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(to bottom right, #3b82f6, #7c3aed);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
        }

        .mobile-app-feature-icon i {
            color: white;
            font-size: 1.25rem;
        }

        .mobile-app-feature-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .mobile-app-feature-subtitle {
            font-size: 0.875rem;
            color: #a1a1aa;
        }

        .mobile-app-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .mobile-app-buttons {
                flex-direction: row;
            }
        }

        .app-store-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            background: #000000;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 1rem;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
        }

        .app-store-btn:hover {
            background: #1f2937;
            transform: scale(1.05);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .app-store-icon {
            width: 2rem;
            height: 2rem;
            position: relative;
        }

        .app-store-icon i {
            font-size: 1.5rem;
        }

        .app-store-text {
            text-align: left;
        }

        .app-store-subtitle {
            font-size: 0.75rem;
            opacity: 0.75;
        }

        .app-store-title {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .mobile-app-visual {
            order: 1;
            display: flex;
            justify-content: center;
        }

        @media (min-width: 1024px) {
            .mobile-app-visual {
                order: 2;
            }
        }

        .phone-mockup {
            position: relative;
        }

        .phone-frame {
            position: relative;
            width: 20rem;
            height: 37.5rem;
            background: linear-gradient(to bottom right, #1f2937, #000000);
            border-radius: 3rem;
            padding: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .phone-screen {
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom right, #2563eb, #7c3aed, #4f46e5);
            border-radius: 2.5rem;
            overflow: hidden;
            position: relative;
        }

        .phone-status {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3rem;
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            color: white;
            font-size: 0.875rem;
        }

        .phone-battery {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .battery-icon {
            width: 1rem;
            height: 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 0.125rem;
        }

        .battery-fill {
            width: 0.75rem;
            height: 0.25rem;
            background: white;
            border-radius: 0.125rem;
        }

        .phone-content {
            padding-top: 3rem;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .phone-header {
            text-align: center;
            color: white;
            margin-bottom: 2rem;
        }

        .phone-header h3 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .phone-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
        }

        .phone-balance {
            color: white;
            font-size: 1.875rem;
            font-weight: bold;
            margin-top: 0.5rem;
        }

        .phone-chart {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            flex: 1;
        }

        .chart-area {
            height: 8rem;
            position: relative;
            background: linear-gradient(to right, rgba(34, 197, 94, 0.2), rgba(59, 130, 246, 0.2));
            border-radius: 0.75rem;
        }

        .phone-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .phone-action {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border-radius: 0.75rem;
            padding: 0.75rem;
            text-align: center;
        }

        .phone-action-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
        }

        .phone-action-value {
            color: white;
            font-weight: 600;
        }

        .phone-home-indicator {
            position: absolute;
            bottom: 0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 8rem;
            height: 0.25rem;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 9999px;
        }

        .phone-notification {
            position: absolute;
            top: -1rem;
            right: -1rem;
            width: 4rem;
            height: 4rem;
            background: #22c55e;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .phone-notification i {
            color: white;
            font-size: 1.25rem;
        }

        .phone-badge {
            position: absolute;
            bottom: -1rem;
            left: -1rem;
            width: 5rem;
            height: 3rem;
            background: #3b82f6;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .phone-badge-text {
            color: white;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .chart-area {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            background: linear-gradient(to top, rgba(96, 165, 250, 0.3), rgba(96, 165, 250, 0.1));
            border-radius: 0.5rem;
        }

        .phone-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }

        .phone-action {
            text-align: center;
            padding: 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
        }

        .phone-action:first-child {
            background: rgba(34, 197, 94, 0.2);
            border: 1px solid rgba(34, 197, 94, 0.5);
        }

        .phone-action:last-child {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.5);
        }

        .phone-action-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .phone-action:first-child .phone-action-label {
            color: #22c55e;
        }

        .phone-action:last-child .phone-action-label {
            color: #ef4444;
        }

        .phone-action-value {
            font-size: 1.125rem;
            font-weight: bold;
            color: white;
        }

        .phone-home-indicator {
            position: absolute;
            bottom: 0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 2rem;
            height: 0.25rem;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 0.125rem;
        }

        .phone-notification {
            position: absolute;
            top: 50%;
            right: -3rem;
            transform: translateY(-50%);
            width: 3rem;
            height: 3rem;
            background: rgba(59, 130, 246, 0.2);
            border: 1px solid rgba(59, 130, 246, 0.5);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .phone-notification i {
            color: #60a5fa;
            font-size: 1.5rem;
        }

        .phone-badge {
            position: absolute;
            bottom: 2rem;
            right: -3rem;
            background: linear-gradient(to right, #ef4444, #f43f5e);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .phone-badge-text {
            text-align: center;
            line-height: 1;
        }

        /* CTA Section */
        .cta {
            position: relative;
            padding: 6rem 0;
            overflow: hidden;
            text-align: center;
        }

        .cta-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, #2563eb, #7c3aed, #4f46e5);
        }

        .cta-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.2);
        }

        .cta-content {
            position: relative;
            z-index: 10;
        }

        .cta-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 2rem;
        }

        .cta-title {
            font-size: 2.25rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            color: white;
        }

        @media (min-width: 768px) {
            .cta-title {
                font-size: 3.75rem;
            }
        }

        .cta-description {
            font-size: 1.25rem;
            margin-bottom: 3rem;
            max-width: 48rem;
            margin-left: auto;
            margin-right: auto;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
        }

        @media (min-width: 768px) {
            .cta-description {
                font-size: 1.5rem;
            }
        }

        .cta-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            justify-content: center;
            align-items: center;
        }

        @media (min-width: 640px) {
            .cta-actions {
                flex-direction: row;
            }
        }

        .cta-btn {
            padding: 1rem 2rem;
            background: white;
            color: #2563eb;
            border-radius: 1rem;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transform: scale(1);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .cta-btn:hover {
            background: #f3f4f6;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(1.05);
        }

        .cta-features {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .cta-feature {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Footer */
        .footer {
            position: relative;
            overflow: hidden;
        }

        .footer-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom right, #09090b, rgba(30, 58, 138, 0.3), rgba(88, 28, 135, 0.3));
        }

        .footer-shapes {
            position: absolute;
            inset: 0;
        }

        .footer-shape-1 {
            position: absolute;
            top: 0;
            left: 25%;
            width: 24rem;
            height: 24rem;
            background: linear-gradient(to bottom right, rgba(59, 130, 246, 0.1), rgba(6, 182, 212, 0.1));
            border-radius: 9999px;
            filter: blur(48px);
            animation: pulse 3s ease-in-out infinite;
        }

        .footer-shape-2 {
            position: absolute;
            bottom: 0;
            right: 25%;
            width: 24rem;
            height: 24rem;
            background: linear-gradient(to bottom right, rgba(147, 51, 234, 0.1), rgba(236, 72, 153, 0.1));
            border-radius: 9999px;
            filter: blur(48px);
            animation: pulse 3s ease-in-out infinite;
            animation-delay: 1s;
        }

        .footer-content {
            position: relative;
            z-index: 10;
        }

        .footer-main {
            padding: 4rem 0;
        }

        @media (min-width: 768px) {
            .footer-main {
                padding: 5rem 0;
            }
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 3rem;
        }

        @media (min-width: 1024px) {
            .footer-grid {
                grid-template-columns: 2fr 1fr 1fr 1fr;
                gap: 3rem;
            }
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .footer-logo {
            width: 3rem;
            height: 3rem;
            background: linear-gradient(to right, #2563eb, #7c3aed);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-logo i {
            color: white;
            font-size: 1.5rem;
        }

        .footer-brand-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }

        .footer-description {
            color: #d1d5db;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            max-width: 28rem;
        }

        .footer-section h3 {
            color: white;
            font-weight: 600;
            font-size: 1.125rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: #d1d5db;
            text-decoration: none;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(8px);
        }

        .footer-bottom-content {
            padding: 1.5rem 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .footer-bottom-content {
                flex-direction: row;
            }
        }

        .footer-copyright {
            color: #9ca3af;
            font-size: 0.875rem;
            text-align: center;
        }

        @media (min-width: 768px) {
            .footer-copyright {
                text-align: left;
            }
        }

        .footer-legal {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
            font-size: 0.875rem;
        }

        .footer-legal a {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-legal a:hover {
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 1023px) {
            .container {
                padding: 0 1rem;
            }
        }

        @media (max-width: 767px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .features-title,
            .platform-title,
            .steps-title,
            .mobile-app-title {
                font-size: 2rem;
            }

            .cta-title {
                font-size: 2rem;
            }
        }

        /* Utility Classes */
        .hidden {
            display: none;
        }

        .block {
            display: block;
        }

        .flex {
            display: flex;
        }

        .grid {
            display: grid;
        }

        .relative {
            position: relative;
        }

        .absolute {
            position: absolute;
        }

        .fixed {
            position: fixed;
        }

        .inset-0 {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .z-10 {
            z-index: 10;
        }

        .z-40 {
            z-index: 40;
        }

        .z-50 {
            z-index: 50;
        }

        .w-full {
            width: 100%;
        }

        .h-full {
            height: 100%;
        }

        .min-h-screen {
            min-height: 100vh;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-medium {
            font-weight: 500;
        }

        .text-white {
            color: white;
        }

        .text-blue-400 {
            color: #60a5fa;
        }

        .bg-transparent {
            background-color: transparent;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .transition {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .hover\:text-blue-400:hover {
            color: #60a5fa;
        }

        .hover\:bg-zinc-800:hover {
            background-color: #27272a;
        }
    </style>
    <script type="module" crossorigin src="/preview/a182aa29-504c-44e4-ab5e-abefe2e5d6fd/3804929/assets/index-i9cGwhgE.js"></script>
</head>
<body>
<!-- Header -->
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo-nav">
                <a  class="logo">
                    <img alt="" src="">
                </a>
                <nav class="nav">
                    <a href="#trading">Trading</a>
                    <a href="#portfolio">Portfolio</a>
                    <a href="#investments">Investments</a>
                    <a href="#marketplace">Marketplace</a>
                    <a href="#services">Services</a>
                    <a href="#insights">Insights</a>
                </nav>
            </div>
            <div class="header-actions">
                <button class="flag-btn">
                    <img alt="US Flag" src="https://demo.mashdiv.com/img/flag/us.webp">
                </button>
                <div class="auth-buttons">
                    @if(auth()->user())
                    <a href="{{route('user.home')}}" class="btn btn-secondary">Dashboard</a>
                    @else
                    <a href="{{route('user.login')}}" class="btn btn-secondary">Log in</a>
                    <a href="{{route('user.register')}}" class="btn btn-primary">Sign up</a>
                    @endif
                </div>
                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    <i class="ri-menu-line"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Menu -->
<div id="mobile-menu" class="mobile-menu">
    <div class="mobile-menu-content">
        <nav class="mobile-nav">
            <a href="#trading">Trading</a>
            <a href="#portfolio">Portfolio</a>
            <a href="#investments">Investments</a>
            <a href="#marketplace">Marketplace</a>
            <a href="#services">Services</a>
            <a href="#insights">Insights</a>
        </nav>
        <div class="mobile-auth">
            @if(auth()->user())
                <a href="{{route('user.home')}}" class="btn btn-secondary">Dashboard</a>
            @else
                <a href="{{route('user.login')}}" class="btn btn-secondary">Log in</a>
                <a href="{{route('user.register')}}" class="btn btn-primary">Sign up</a>
            @endif
        </div>
    </div>
</div>

<!-- Hero Section -->
<section id="trading" class="hero">
    <div class="hero-bg">
        <div class="hero-gradient"></div>
        <div class="hero-shapes">
            <div class="hero-shape hero-shape-1"></div>
            <div class="hero-shape hero-shape-2"></div>
            <div class="hero-shape hero-shape-3"></div>
        </div>
    </div>
    <div class="container hero-content">
        <div class="hero-grid">
            <div class="hero-text">
                <div class="hero-badge">
                    #1 Crypto Trading Platform
                </div>
                <h1 class="hero-title">
                    <span class="hero-title-line">Trade Crypto</span>
                    <span class="hero-title-gradient">like a pro</span>
                </h1>
                <p class="hero-description">
                    Advanced trading tools, lightning-fast execution, and unmatched security. Join millions of traders worldwide.
                </p>
                <div class="hero-cta">
                    <a href="{{route('user.register')}}" class="hero-cta-btn">
                        Start Trading Free
                    </a>
                </div>
                <div class="hero-features">
                    <div class="hero-feature">
                        <span>Secure Trading</span>
                    </div>
                    <div class="hero-feature">
                        <span>Real-time Data</span>
                    </div>
                    <div class="hero-feature">
                        <span>24/7 Support</span>
                    </div>
                </div>
            </div>
            <div class="hero-trading">
                <div class="trading-card">
                    <div class="trading-content">
                        <div class="trading-header">
                            <div>Asset</div>
                            <div class="trading-header-price">
                                <span>Price</span> / <span>Cap</span>
                            </div>
                            <div class="trading-header-change">24h</div>
                        </div>
                        <div class="trading-list" id="trading-list">
                            <!-- Bitcoin -->
                            <div class="trading-item" data-symbol="bitcoin">
                                <div class="trading-asset">
                                    <div class="trading-icon">
                                        <img alt="BTC" src="https://demo.mashdiv.com/img/crypto/btc.webp">
                                    </div>
                                    <div>
                                        <div class="trading-symbol">BTC</div>
                                        <div class="trading-name">BTCUSDT</div>
                                    </div>
                                </div>
                                <div class="trading-price-col">
                                    <div class="trading-price" id="btc-price">$--,---.--</div>
                                    <div class="trading-cap" id="btc-cap">$--.--B</div>
                                </div>
                                <div class="trading-change-col">
                                    <div class="trading-change" id="btc-change">--.--%</div>
                                </div>
                            </div>
                            <!-- Ethereum -->
                            <div class="trading-item" data-symbol="ethereum">
                                <div class="trading-asset">
                                    <div class="trading-icon">
                                        <img alt="ETH" src="https://demo.mashdiv.com/img/crypto/eth.webp">
                                    </div>
                                    <div>
                                        <div class="trading-symbol">ETH</div>
                                        <div class="trading-name">ETHUSDT</div>
                                    </div>
                                </div>
                                <div class="trading-price-col">
                                    <div class="trading-price" id="eth-price">$--,---.--</div>
                                    <div class="trading-cap" id="eth-cap">$--.--B</div>
                                </div>
                                <div class="trading-change-col">
                                    <div class="trading-change" id="eth-change">--.--%</div>
                                </div>
                            </div>
                            <!-- USD Coin -->
                            <div class="trading-item" data-symbol="usd-coin">
                                <div class="trading-asset">
                                    <div class="trading-icon">
                                        <img alt="USDC" src="https://demo.mashdiv.com/img/crypto/usdc.webp">
                                    </div>
                                    <div>
                                        <div class="trading-symbol">USDC</div>
                                        <div class="trading-name">USDCUSDT</div>
                                    </div>
                                </div>
                                <div class="trading-price-col">
                                    <div class="trading-price" id="usdc-price">$--.----</div>
                                    <div class="trading-cap" id="usdc-cap">$--.--K</div>
                                </div>
                                <div class="trading-change-col">
                                    <div class="trading-change" id="usdc-change">--.--%</div>
                                </div>
                            </div>
                            <!-- Solana -->
                            <div class="trading-item" data-symbol="solana">
                                <div class="trading-asset">
                                    <div class="trading-icon">
                                        <img alt="SOL" src="https://demo.mashdiv.com/img/crypto/sol.webp">
                                    </div>
                                    <div>
                                        <div class="trading-symbol">SOL</div>
                                        <div class="trading-name">SOLUSDT</div>
                                    </div>
                                </div>
                                <div class="trading-price-col">
                                    <div class="trading-price" id="sol-price">$--.--</div>
                                    <div class="trading-cap" id="sol-cap">$--.--M</div>
                                </div>
                                <div class="trading-change-col">
                                    <div class="trading-change" id="sol-change">--.--%</div>
                                </div>
                            </div>
                            <!-- Binance Coin -->
                            <div class="trading-item" data-symbol="binancecoin">
                                <div class="trading-asset">
                                    <div class="trading-icon">
                                        <img alt="BNB" src="https://demo.mashdiv.com/img/crypto/bnb.webp">
                                    </div>
                                    <div>
                                        <div class="trading-symbol">BNB</div>
                                        <div class="trading-name">BNBUSDT</div>
                                    </div>
                                </div>
                                <div class="trading-price-col">
                                    <div class="trading-price" id="bnb-price">$--.--</div>
                                    <div class="trading-cap" id="bnb-cap">$--.--M</div>
                                </div>
                                <div class="trading-change-col">
                                    <div class="trading-change" id="bnb-change">--.--%</div>
                                </div>
                            </div>
                        </div>
                        <div class="trading-footer">
                            <a href="#market">View All Markets</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ticker Section -->
<section class="ticker">
    <div class="ticker-container">
        <div class="ticker-content" id="ticker-content">
            <!-- Ticker items will be populated by JavaScript -->
            <div class="ticker-item">
                <div class="ticker-symbol">BTCUSDT</div>
                <div class="ticker-price" id="ticker-btc-price">--,---.--</div>
                <div class="ticker-change" id="ticker-btc-change">--.--%</div>
            </div>
            <div class="ticker-item">
                <div class="ticker-symbol">ETHUSDT</div>
                <div class="ticker-price" id="ticker-eth-price">--,---.--</div>
                <div class="ticker-change" id="ticker-eth-change">--.--%</div>
            </div>
            <div class="ticker-item">
                <div class="ticker-symbol">USDCUSDT</div>
                <div class="ticker-price" id="ticker-usdc-price">--.----</div>
                <div class="ticker-change" id="ticker-usdc-change">--.--%</div>
            </div>
            <div class="ticker-item">
                <div class="ticker-symbol">SOLUSDT</div>
                <div class="ticker-price" id="ticker-sol-price">--.--</div>
                <div class="ticker-change" id="ticker-sol-change">--.--%</div>
            </div>
            <div class="ticker-item">
                <div class="ticker-symbol">BNBUSDT</div>
                <div class="ticker-price" id="ticker-bnb-price">--.--</div>
                <div class="ticker-change" id="ticker-bnb-change">--.--%</div>
            </div>
            <!-- Duplicate for seamless scroll -->
            <div class="ticker-item">
                <div class="ticker-symbol">BTCUSDT</div>
                <div class="ticker-price" id="ticker-btc-price-dup">--,---.--</div>
                <div class="ticker-change" id="ticker-btc-change-dup">--.--%</div>
            </div>
            <div class="ticker-item">
                <div class="ticker-symbol">ETHUSDT</div>
                <div class="ticker-price" id="ticker-eth-price-dup">--,---.--</div>
                <div class="ticker-change" id="ticker-eth-change-dup">--.--%</div>
            </div>
            <div class="ticker-item">
                <div class="ticker-symbol">USDCUSDT</div>
                <div class="ticker-price" id="ticker-usdc-price-dup">--.----</div>
                <div class="ticker-change" id="ticker-usdc-change-dup">--.--%</div>
            </div>
            <div class="ticker-item">
                <div class="ticker-symbol">SOLUSDT</div>
                <div class="ticker-price" id="ticker-sol-price-dup">--.--</div>
                <div class="ticker-change" id="ticker-sol-change-dup">--.--%</div>
            </div>
            <div class="ticker-item">
                <div class="ticker-symbol">BNBUSDT</div>
                <div class="ticker-price" id="ticker-bnb-price-dup">--.--</div>
                <div class="ticker-change" id="ticker-bnb-change-dup">--.--%</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="portfolio" class="features">
    <div class="features-content">
        <div class="container">
            <div class="features-header">
                <div class="features-badge">
                    Why Choose Us
                </div>
                <h2 class="features-title">
                    Built for <span class="features-title-gradient">Professional Traders</span>
                </h2>
                <p class="features-description">
                    Experience the most advanced trading platform with unmatched security and professional-grade tools for traders of all levels.
                </p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon yellow">
                        <i class="ri-flashlight-line"></i>
                    </div>
                    <h3 class="feature-title">Fast Execution</h3>
                    <p class="feature-description">
                        Execute trades quickly with our reliable matching engine and responsive trading interface.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon green">
                        <i class="ri-shield-check-line"></i>
                    </div>
                    <h3 class="feature-title">Secure Platform</h3>
                    <p class="feature-description">
                        Multi-layer security with encryption, secure wallets, and authentication protocols to protect your assets.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon blue">
                        <i class="ri-line-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Real-time Charts</h3>
                    <p class="feature-description">
                        Professional charting tools with technical indicators and market data for informed trading decisions.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon purple">
                        <i class="ri-team-line"></i>
                    </div>
                    <h3 class="feature-title">User Community</h3>
                    <p class="feature-description">
                        Join our trading community and connect with other traders to share insights and strategies.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon red">
                        <i class="ri-file-list-3-line"></i>
                    </div>
                    <h3 class="feature-title">Order Types</h3>
                    <p class="feature-description">
                        Various order types including market, limit, and stop orders for flexible trading strategies.
                    </p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon indigo">
                        <i class="ri-money-dollar-circle-line"></i>
                    </div>
                    <h3 class="feature-title">Competitive Fees</h3>
                    <p class="feature-description">
                        Transparent fee structure with competitive rates for both makers and takers.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Platform Section -->
<section id="investments" class="platform">
    <div class="container">
        <div class="platform-grid">
            <div>
                <div class="platform-badge">
                    Global Platform
                </div>
                <h2 class="platform-title">
                    Reliable <span class="platform-title-gradient">Trading Platform</span>
                </h2>
                <p class="platform-description">
                    Experience secure cryptocurrency trading with advanced security measures and professional tools.
                </p>
                <div class="platform-features">
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="ri-user-line"></i>
                        </div>
                        <div>
                            <div class="platform-feature-title">User-Friendly</div>
                            <div class="platform-feature-subtitle">Easy Interface</div>
                        </div>
                    </div>
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="ri-global-line"></i>
                        </div>
                        <div>
                            <div class="platform-feature-title">Global Access</div>
                            <div class="platform-feature-subtitle">Trade Anywhere</div>
                        </div>
                    </div>
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="ri-shield-check-line"></i>
                        </div>
                        <div>
                            <div class="platform-feature-title">Secure Trading</div>
                            <div class="platform-feature-subtitle">Protected Assets</div>
                        </div>
                    </div>
                    <div class="platform-feature">
                        <div class="platform-feature-icon">
                            <i class="ri-customer-service-2-line"></i>
                        </div>
                        <div>
                            <div class="platform-feature-title">Quality Service</div>
                            <div class="platform-feature-subtitle">24/7 Support</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="platform-card">
                    <div class="platform-card-content">
                        <h3 class="platform-card-title">Platform Features</h3>
                        <ul class="platform-list">
                            <li class="platform-list-item">
                                <i class="ri-check-line"></i>
                                <span>Real-time market data and price feeds</span>
                            </li>
                            <li class="platform-list-item">
                                <i class="ri-check-line"></i>
                                <span>Multiple order types for trading flexibility</span>
                            </li>
                            <li class="platform-list-item">
                                <i class="ri-check-line"></i>
                                <span>Responsive web interface for all devices</span>
                            </li>
                            <li class="platform-list-item">
                                <i class="ri-check-line"></i>
                                <span>Customer support and help resources</span>
                            </li>
                            <li class="platform-list-item">
                                <i class="ri-check-line"></i>
                                <span>Secure wallet and account management</span>
                            </li>
                            <li class="platform-list-item">
                                <i class="ri-check-line"></i>
                                <span>Professional charting and analysis tools</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Steps Section -->
<section id="marketplace" class="steps">
    <div class="container">
        <div class="steps-header">
            <div class="steps-badge">
                Get Started
            </div>
            <h2 class="steps-title">
                Start Your <span class="steps-title-gradient">Trading Journey</span>
            </h2>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-content">
                    <div class="step-header">
                        <div class="step-icon blue">
                            <i class="ri-user-add-line"></i>
                        </div>
                        <div class="step-number">01</div>
                    </div>
                    <h3 class="step-title">Create Account</h3>
                    <p class="step-description">
                        Sign up for your free trading account with email verification and secure password setup.
                    </p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-content">
                    <div class="step-header">
                        <div class="step-icon purple">
                            <i class="ri-wallet-3-line"></i>
                        </div>
                        <div class="step-number">02</div>
                    </div>
                    <h3 class="step-title">Secure Your Wallet</h3>
                    <p class="step-description">
                        Set up your secure wallet with proper authentication and backup recovery methods.
                    </p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-content">
                    <div class="step-header">
                        <div class="step-icon orange">
                            <i class="ri-exchange-line"></i>
                        </div>
                        <div class="step-number">03</div>
                    </div>
                    <h3 class="step-title">Start Trading</h3>
                    <p class="step-description">
                        Explore markets, analyze charts, and execute your first trades with our intuitive platform.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile App Section -->
<section id="services" class="mobile-app">
    <div class="mobile-app-bg">
        <div class="mobile-app-shape-1"></div>
        <div class="mobile-app-shape-2"></div>
        <div class="mobile-app-overlay"></div>
    </div>
    <div class="mobile-app-content">
        <div class="container">
            <div class="mobile-app-grid">
                <div class="mobile-app-text">
                    <div class="mobile-app-badge">
                        Download Our App
                    </div>
                    <h2 class="mobile-app-title">
                        Trade on the Go
                        <span class="mobile-app-title-line">
                                Anytime, Anywhere
                            </span>
                    </h2>
                    <p class="mobile-app-description">
                        Experience seamless cryptocurrency trading with our mobile app. Get real-time market data, secure transactions, and professional trading tools in your pocket.
                    </p>
                    <div class="mobile-app-features">
                        <div class="mobile-app-feature">
                            <div class="mobile-app-feature-icon">
                                <i class="ri-shield-check-line"></i>
                            </div>
                            <h3 class="mobile-app-feature-title">Secure Trading</h3>
                            <p class="mobile-app-feature-subtitle">Bank-level security</p>
                        </div>
                        <div class="mobile-app-feature">
                            <div class="mobile-app-feature-icon">
                                <i class="ri-flashlight-line"></i>
                            </div>
                            <h3 class="mobile-app-feature-title">Lightning Fast</h3>
                            <p class="mobile-app-feature-subtitle">Instant transactions</p>
                        </div>
                        <div class="mobile-app-feature">
                            <div class="mobile-app-feature-icon">
                                <i class="ri-user-line"></i>
                            </div>
                            <h3 class="mobile-app-feature-title">User Friendly</h3>
                            <p class="mobile-app-feature-subtitle">Intuitive interface</p>
                        </div>
                    </div>
                    <div class="mobile-app-buttons">

                        <a href="https://apk.nysefinance.com/nysefinance.apk" target="_blank" rel="noopener noreferrer" class="app-store-btn">
                            <div class="app-store-icon">
                                <i class="ri-google-play-line"></i>
                            </div>
                            <div class="app-store-text">
                                <div class="app-store-subtitle">Get it on</div>
                                <div class="app-store-title">Google Play</div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="mobile-app-visual">
                    <div class="phone-mockup">
                        <div class="phone-frame">
                            <div class="phone-screen">
                                <div class="phone-status">
                                    <span>9:41</span>
                                    <div class="phone-battery">
                                        <div class="battery-icon">
                                            <div class="battery-fill"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="phone-content">
                                    <div class="phone-header">
                                        <h3>Trading Dashboard</h3>
                                        <p>Portfolio Balance</p>
                                        <p class="phone-balance">$24,567.89</p>
                                    </div>
                                    <div class="phone-chart">
                                        <div class="chart-area"></div>
                                    </div>
                                    <div class="phone-actions">
                                        <div class="phone-action">
                                            <div class="phone-action-label">Buy</div>
                                            <div class="phone-action-value">BTC</div>
                                        </div>
                                        <div class="phone-action">
                                            <div class="phone-action-label">Sell</div>
                                            <div class="phone-action-value">ETH</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="phone-home-indicator"></div>
                        </div>
                        <div class="phone-notification">
                            <i class="ri-notification-line"></i>
                        </div>
                        <div class="phone-badge">
                            <div class="phone-badge-text">24/7</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section id="insights" class="cta">
    <div class="cta-bg"></div>
    <div class="cta-overlay"></div>
    <div class="cta-content">
        <div class="container">
            <div class="cta-badge">
                Start Your Journey
            </div>
            <h2 class="cta-title">
                Ready to Start Trading?
            </h2>
            <p class="cta-description">
                Join our platform and experience secure cryptocurrency trading with professional tools and real-time market data.
            </p>
            <div class="cta-actions">
                <a href="{{route('user.register')}}" class="cta-btn">
                    Create Free Account
                </a>
                <div class="cta-features">
                    <div class="cta-feature">
                        <span>No Credit Card Required</span>
                    </div>
                    <div class="cta-feature">
                        <span>Free Registration</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-bg"></div>
    <div class="footer-shapes">
        <div class="footer-shape-1"></div>
        <div class="footer-shape-2"></div>
    </div>
    <div class="footer-content">
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    <div>
                        <div class="footer-brand">
                            <div class="footer-logo">
                                <i class="ri-currency-line"></i>
                            </div>
                            <span class="footer-brand-name">{{ gs()->siteName(__($pageTitle)) }}</span>
                        </div>
                        <p class="footer-description">
                            {{ gs()->siteName(__($pageTitle)) }} is a cryptocurrency exchange platform, where you can trade Bitcoin, Ethereum, Litecoin, and other cryptocurrencies.
                        </p>
                    </div>
                    <div class="footer-section">

                    </div>
                    <div class="footer-section">


                    </div>
                    <div class="footer-section">

                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-content">
                    <div class="footer-copyright">
                        © {{date('Y')}} {{ gs()->siteName(__($pageTitle)) }}. All rights reserved.
                    </div>
                    <div class="footer-legal">
                        <a href="/privacy">Privacy Policy</a>
                        <a href="/terms">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>



<script>
    // Mobile menu toggle
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('active');
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileMenu = document.getElementById('mobile-menu');
        const menuBtn = document.querySelector('.mobile-menu-btn');

        if (!mobileMenu.contains(event.target) && !menuBtn.contains(event.target)) {
            mobileMenu.classList.remove('active');
        }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
            // Close mobile menu if open
            document.getElementById('mobile-menu').classList.remove('active');
        });
    });

    // Header background on scroll
    window.addEventListener('scroll', function() {
        const header = document.querySelector('.header');
        if (window.scrollY > 100) {
            header.style.background = 'rgba(9, 9, 11, 0.98)';
        } else {
            header.style.background = 'rgba(9, 9, 11, 0.95)';
        }
    });

    // Cryptocurrency API Configuration
    const CRYPTO_CONFIG = {
        coins: [
            { id: 'bitcoin', symbol: 'BTC', name: 'Bitcoin' },
            { id: 'ethereum', symbol: 'ETH', name: 'Ethereum' },
            { id: 'usd-coin', symbol: 'USDC', name: 'USD Coin' },
            { id: 'solana', symbol: 'SOL', name: 'Solana' },
            { id: 'binancecoin', symbol: 'BNB', name: 'BNB' }
        ],
        updateInterval: 30000, // Update every 30 seconds
        apiUrl: 'https://api.coingecko.com/api/v3/simple/price'
    };

    // Format numbers for display
    function formatPrice(price) {
        if (price >= 1000) {
            return price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        } else if (price >= 1) {
            return price.toFixed(2);
        } else {
            return price.toFixed(4);
        }
    }

    function formatMarketCap(marketCap) {
        if (marketCap >= 1e9) {
            return `$${(marketCap / 1e9).toFixed(2)}B`;
        } else if (marketCap >= 1e6) {
            return `$${(marketCap / 1e6).toFixed(2)}M`;
        } else if (marketCap >= 1e3) {
            return `$${(marketCap / 1e3).toFixed(2)}K`;
        } else {
            return `$${marketCap.toFixed(2)}`;
        }
    }

    function formatPercentage(percentage) {
        return `${percentage >= 0 ? '+' : ''}${percentage.toFixed(2)}%`;
    }

    // Update price display with animation
    function updatePriceDisplay(symbol, price, marketCap, change24h) {
        const priceElement = document.getElementById(`${symbol.toLowerCase()}-price`);
        const capElement = document.getElementById(`${symbol.toLowerCase()}-cap`);
        const changeElement = document.getElementById(`${symbol.toLowerCase()}-change`);

        if (priceElement) {
            priceElement.textContent = `$${formatPrice(price)}`;
            priceElement.classList.add('price-updating');
            setTimeout(() => priceElement.classList.remove('price-updating'), 300);
        }

        if (capElement) {
            capElement.textContent = formatMarketCap(marketCap);
        }

        if (changeElement) {
            const isPositive = change24h >= 0;
            changeElement.textContent = formatPercentage(change24h);
            changeElement.className = `trading-change ${isPositive ? 'positive' : ''}`;
        }
    }

    // Update ticker display
    function updateTickerDisplay(symbol, price, change24h) {
        const priceElement = document.getElementById(`ticker-${symbol.toLowerCase()}-price`);
        const priceDupElement = document.getElementById(`ticker-${symbol.toLowerCase()}-price-dup`);
        const changeElement = document.getElementById(`ticker-${symbol.toLowerCase()}-change`);
        const changeDupElement = document.getElementById(`ticker-${symbol.toLowerCase()}-change-dup`);

        const isPositive = change24h >= 0;

        if (priceElement) {
            priceElement.textContent = formatPrice(price);
        }
        if (priceDupElement) {
            priceDupElement.textContent = formatPrice(price);
        }
        if (changeElement) {
            changeElement.textContent = formatPercentage(change24h);
            changeElement.className = `ticker-change ${isPositive ? 'positive' : ''}`;
        }
        if (changeDupElement) {
            changeDupElement.textContent = formatPercentage(change24h);
            changeDupElement.className = `ticker-change ${isPositive ? 'positive' : ''}`;
        }
    }

    // Fetch cryptocurrency data
    async function fetchCryptoData() {
        try {
            const coinIds = CRYPTO_CONFIG.coins.map(coin => coin.id).join(',');
            const url = `${CRYPTO_CONFIG.apiUrl}?ids=${coinIds}&vs_currencies=usd&include_market_cap=true&include_24hr_change=true&include_24hr_vol=true`;

            const response = await fetch(url);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            // Update display for each cryptocurrency
            CRYPTO_CONFIG.coins.forEach(coin => {
                const coinData = data[coin.id];
                if (coinData) {
                    updatePriceDisplay(coin.symbol, coinData.usd, coinData.usd_market_cap, coinData.usd_24h_change);
                    updateTickerDisplay(coin.symbol, coinData.usd, coinData.usd_24h_change);
                }
            });

            console.log('Cryptocurrency data updated successfully');

        } catch (error) {
            console.error('Error fetching cryptocurrency data:', error);

            // Show error state
            CRYPTO_CONFIG.coins.forEach(coin => {
                const priceElement = document.getElementById(`${coin.symbol.toLowerCase()}-price`);
                const changeElement = document.getElementById(`${coin.symbol.toLowerCase()}-change`);

                if (priceElement) {
                    priceElement.textContent = 'Error';
                    priceElement.style.color = '#ef4444';
                }
                if (changeElement) {
                    changeElement.textContent = 'Error';
                    changeElement.style.color = '#ef4444';
                }
            });
        }
    }

    // Initialize cryptocurrency data
    function initCryptoData() {
        // Set loading state
        CRYPTO_CONFIG.coins.forEach(coin => {
            const priceElement = document.getElementById(`${coin.symbol.toLowerCase()}-price`);
            if (priceElement) {
                priceElement.textContent = 'Loading...';
            }
        });

        // Fetch initial data
        fetchCryptoData();

        // Set up periodic updates
        setInterval(fetchCryptoData, CRYPTO_CONFIG.updateInterval);
    }

    // Start the crypto data updates when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize after a short delay to ensure DOM is ready
        setTimeout(initCryptoData, 1000);
    });

    // Add click handlers for trading items
    document.addEventListener('DOMContentLoaded', function() {
        const tradingItems = document.querySelectorAll('.trading-item');
        tradingItems.forEach(item => {
            item.addEventListener('click', function() {
                const symbol = this.dataset.symbol;
                console.log(`Trading clicked for: ${symbol}`);
                // Add your trading logic here
            });
        });
    });
</script>
</body>
</html>
