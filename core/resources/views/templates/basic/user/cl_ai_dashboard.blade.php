@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="crypto-container">
        <!-- Top Navigation Tabs -->
        <div class="main-tabs">
            <div class="tab active" data-tab="exchange">Exchange</div>
            <div class="tab" data-tab="web3">WEB3</div>
        </div>

        <!-- Search and Navigation Bar -->
        <div class="search-bar-container">
            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <span class="search-text">USDTB/USDT</span>
                <i class="fa-solid fa-qrcode qr-icon"></i>
            </div>
        </div>

        <!-- Tab Content Container -->
        <div class="tab-content-container">
            <!-- Exchange Tab Content -->
            <div class="tab-content active" id="exchange-content">
                <!-- Rewards Banner -->
                <div class="rewards-section">
                    <div class="rewards-header">
                        <span>More Rewards</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                    <h2 class="deposit-header">Deposit Now to Start Trading!</h2>
                    <p class="pairs-text">Over 200 Derivatives Contracts and 270 Spot Pairs available!</p>

                    <button class="deposit-button">
                        Deposit/Buy with Fiat/P2P
                    </button>
                </div>

                <!-- Quick Access Icons -->
                <div class="quick-access">
                    <div class="quick-item" data-action="deposit">
                        <div class="icon-wrapper">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <span>Deposit</span>
                    </div>

                    <div class="quick-item" data-action="p2p">
                        <div class="icon-wrapper p2p">
                            <span>P2P</span>
                        </div>
                        <span>P2P Trading</span>
                    </div>

                    <div class="quick-item" data-action="card">
                        <div class="icon-wrapper">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <span>Card</span>
                    </div>

                    <div class="quick-item" data-action="rewards">
                        <div class="icon-wrapper">
                            <i class="fa-solid fa-gift"></i>
                        </div>
                        <span>Rewards Hub</span>
                    </div>
                </div>

                <!-- Second Row of Quick Access -->
                <div class="quick-access">
                    <div class="quick-item" data-action="token">
                        <div class="icon-wrapper">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <span>Token Splash</span>
                    </div>

                    <div class="quick-item" data-action="daily">
                        <div class="icon-wrapper">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <span>Daily Delight</span>
                    </div>

                    <div class="quick-item" data-action="ai">
                        <div class="icon-wrapper ai">
                            <i class="fa-solid fa-robot"></i>
                        </div>
                        <span>AI-DOL</span>
                    </div>

                    <div class="quick-item" data-action="more">
                        <div class="icon-wrapper">
                            <i class="fa-solid fa-ellipsis"></i>
                        </div>
                        <span>More</span>
                    </div>
                </div>

                <!-- Tournament Banner -->
                <div class="tournament-banner">
                    <div class="tournament-info">
                        <h3>TradeMasters Grand Prix 2025 Series 1</h3>
                        <i class="fa-solid fa-arrow-right gold-arrow"></i>
                    </div>
                    <div class="tournament-logo">
                        <img src="/api/placeholder/100/50" alt="TradeMasters Logo">
                    </div>
                </div>

                <!-- Trading Categories -->
                <div class="trading-categories">
                    <div class="category-list">
                        <div class="category active" data-category="favorites">Favorites</div>
                        <div class="category" data-category="hot">Hot</div>
                        <div class="category" data-category="new">New</div>
                        <div class="category" data-category="gainers">Gainers</div>
                        <div class="category" data-category="losers">Losers</div>
                        <div class="category" data-category="turnover">Turnover</div>
                    </div>
                    <div class="type-tabs">
                        <div class="type-tab" data-type="spot">Spot</div>
                        <div class="type-tab active" data-type="derivatives">Derivatives</div>
                    </div>
                    <div class="pairs-header">
                        <span class="pairs-title">Trading Pairs</span>
                        <span class="price-title">Price</span>
                        <span class="change-title">24H Change</span>
                    </div>

                    <!-- Trading Pairs List -->
                    <div class="trading-pairs">
                        <!-- This would be dynamically generated in real app -->
                        <div class="trading-pair-item" data-pair="BTC/USDT">
                            <div class="pair-info">
                                <div class="pair-icon">
                                    <i class="fa-brands fa-bitcoin"></i>
                                </div>
                                <div class="pair-name">BTC/USDT</div>
                            </div>
                            <div class="pair-price">57,243.50</div>
                            <div class="pair-change positive">+2.34%</div>
                        </div>
                        <div class="trading-pair-item" data-pair="ETH/USDT">
                            <div class="pair-info">
                                <div class="pair-icon eth">
                                    <i class="fa-brands fa-ethereum"></i>
                                </div>
                                <div class="pair-name">ETH/USDT</div>
                            </div>
                            <div class="pair-price">3,052.75</div>
                            <div class="pair-change negative">-1.21%</div>
                        </div>
                        <div class="trading-pair-item" data-pair="SOL/USDT">
                            <div class="pair-info">
                                <div class="pair-icon sol">
                                    <i class="fa-solid fa-sun"></i>
                                </div>
                                <div class="pair-name">SOL/USDT</div>
                            </div>
                            <div class="pair-price">142.87</div>
                            <div class="pair-change positive">+5.32%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- WEB3 Tab Content -->
            <div class="tab-content" id="web3-content">
                <div class="web3-container">
                    <div class="section-header">
                        <h2>Web3 Services</h2>
                        <p>Explore decentralized applications and services</p>
                    </div>

                    <!-- DApp Categories -->
                    <div class="dapp-categories">
                        <div class="dapp-category active">
                            <i class="fa-solid fa-right-left"></i>
                            <span>DEX</span>
                        </div>
                        <div class="dapp-category">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                            <span>Lending</span>
                        </div>
                        <div class="dapp-category">
                            <i class="fa-solid fa-gamepad"></i>
                            <span>Gaming</span>
                        </div>
                        <div class="dapp-category">
                            <i class="fa-solid fa-image"></i>
                            <span>NFTs</span>
                        </div>
                    </div>

                    <!-- Featured DApps -->
                    <div class="featured-dapps">
                        <h3>Featured DApps</h3>
                        <div class="dapp-grid">
                            <div class="dapp-card" data-dapp="uniswap">
                                <div class="dapp-logo">
                                    <img src="/api/placeholder/50/50" alt="UniSwap Logo">
                                </div>
                                <div class="dapp-info">
                                    <h4>UniSwap</h4>
                                    <p>Decentralized Token Exchange</p>
                                </div>
                            </div>
                            <div class="dapp-card" data-dapp="aave">
                                <div class="dapp-logo">
                                    <img src="/api/placeholder/50/50" alt="Aave Logo">
                                </div>
                                <div class="dapp-info">
                                    <h4>Aave</h4>
                                    <p>Lending &amp; Borrowing Protocol</p>
                                </div>
                            </div>
                            <div class="dapp-card" data-dapp="axie">
                                <div class="dapp-logo">
                                    <img src="/api/placeholder/50/50" alt="Axie Infinity Logo">
                                </div>
                                <div class="dapp-info">
                                    <h4>Axie Infinity</h4>
                                    <p>Play-to-Earn NFT Game</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation -->
        <div class="bottom-nav">
            <div class="nav-item active" data-nav="home">
                <i class="fa-solid fa-house"></i>
                <span>Home</span>
            </div>
            <div class="nav-item" data-nav="markets">
                <i class="fa-solid fa-chart-line"></i>
                <span>Markets</span>
            </div>
            <div class="nav-item" data-nav="trade">
                <i class="fa-solid fa-right-left"></i>
                <span>Trade</span>
            </div>
            <div class="nav-item" data-nav="earn">
                <i class="fa-solid fa-sack-dollar"></i>
                <span>Earn</span>
            </div>
            <div class="nav-item" data-nav="assets">
                <i class="fa-solid fa-wallet"></i>
                <span>Assets</span>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            // Top navigation tabs
            $('.tab').on('click', function() {
                const tabId = $(this).data('tab');

                // Update active tab
                $('.tab').removeClass('active');
                $(this).addClass('active');

                // Show corresponding content
                $('.tab-content').removeClass('active');
                $(`#${tabId}-content`).addClass('active');
            });

            // Trading category tabs
            $('.category').on('click', function() {
                $('.category').removeClass('active');
                $(this).addClass('active');
                // Load corresponding trading pairs (simulation for demo)
                simulateDataUpdate('category', $(this).data('category'));
            });

            // Trading type tabs (Spot/Derivatives)
            $('.type-tab').on('click', function() {
                $('.type-tab').removeClass('active');
                $(this).addClass('active');
                // Load corresponding market type (simulation for demo)
                simulateDataUpdate('type', $(this).data('type'));
            });

            // Bottom navigation
            $('.nav-item').on('click', function() {
                $('.nav-item').removeClass('active');
                $(this).addClass('active');

                // Show appropriate content based on navigation
                const navSection = $(this).data('nav');

                // For demo purposes, just show an alert
                if (navSection !== 'home') {
                    alert(`Navigating to ${navSection} section`);
                    // Then reset to home for the demo
                    setTimeout(() => {
                        $('.nav-item').removeClass('active');
                        $('.nav-item[data-nav="home"]').addClass('active');
                    }, 500);
                }
            });

            // Quick access items
            $('.quick-item').on('click', function() {
                const action = $(this).data('action');
                // Animate the icon for better feedback
                $(this).find('.icon-wrapper').addClass('clicked');
                setTimeout(() => {
                    $(this).find('.icon-wrapper').removeClass('clicked');
                }, 300);

                // For demo purposes, show an alert
                alert(`Action triggered: ${action}`);
            });

            // WEB3 DApp categories
            $('.dapp-category').on('click', function() {
                $('.dapp-category').removeClass('active');
                $(this).addClass('active');
                // Load corresponding DApps (simulation for demo)
                simulateDataUpdate('dapp-category', $(this).text().trim());
            });

            // Make trading pair items clickable
            $('.trading-pair-item').on('click', function() {
                const pair = $(this).data('pair');
                alert(`Opening trading page for ${pair}`);
            });

            // Make DApp cards clickable
            $('.dapp-card').on('click', function() {
                const dapp = $(this).data('dapp');
                alert(`Opening ${dapp} DApp`);
            });

            // Tournament banner click
            $('.tournament-banner').on('click', function() {
                alert('Opening TradeMasters Grand Prix 2025 Series 1 details');
            });

            // Function to simulate data updates
            function simulateDataUpdate(type, value) {
                console.log(`Updating data for ${type}: ${value}`);
                // This would normally call an API or load different data
                // For demo purposes, just show a loading effect

                if (type === 'category' || type === 'type') {
                    const container = $('.trading-pairs');
                    container.css('opacity', 0.5);
                    setTimeout(() => {
                        container.css('opacity', 1);
                    }, 500);
                } else if (type === 'dapp-category') {
                    const container = $('.dapp-grid');
                    container.css('opacity', 0.5);
                    setTimeout(() => {
                        container.css('opacity', 1);
                    }, 500);
                }
            }

            // Responsive adjustments based on screen size
            function handleResponsiveLayout() {
                if (window.innerWidth >= 992) {
                    $('.crypto-container').addClass('desktop-view');
                    // Additional desktop-specific adjustments
                } else {
                    $('.crypto-container').removeClass('desktop-view');
                    // Additional mobile-specific adjustments
                }
            }

            // Initial call and resize listener
            handleResponsiveLayout();
            $(window).resize(handleResponsiveLayout);
        });
    </script>
@endpush


@push('style')
    <style>
        /* Base Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .crypto-container {
            max-width: 480px;
            margin: 0 auto;
            position: relative;
            min-height: 100vh;
            padding-bottom: 70px; /* For bottom navigation */
        }

        /* Top Bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            padding: 8px 16px;
            background-color: #121212;
        }

        .status-icons {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .time {
            font-weight: bold;
        }

        .right-icons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        /* Tab Navigation */
        .main-tabs {
            display: flex;
            background-color: #1e1e1e;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 10px 15px;
        }

        .tab {
            flex: 1;
            text-align: center;
            font-weight: 500;
            color: #aaaaaa;
            cursor: pointer;
            padding: 8px 0;
            transition: color 0.3s ease;
        }

        .tab.active {
            color: #ffffff;
        }

        /* Search Bar */
        .search-bar-container {
            padding: 10px 15px;
        }

        .search-bar {
            background-color: #1e1e1e;
            padding: 10px 15px;
            border-radius: 25px;
            display: flex;
            align-items: center;
        }

        .search-icon {
            color: #ff9500;
            margin-right: 10px;
        }

        .search-text {
            flex: 1;
            color: #ffffff;
        }

        .qr-icon {
            color: #ffffff;
        }

        /* Tab Content */
        .tab-content-container {
            position: relative;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Rewards Section */
        .rewards-section {
            background-color: #1e1e1e;
            margin: 15px;
            padding: 15px;
            border-radius: 10px;
        }

        .rewards-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #888888;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .deposit-header {
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .pairs-text {
            color: #aaaaaa;
            font-size: 0.85rem;
            margin-bottom: 15px;
        }

        .deposit-button {
            background-color: #f0b90b;
            color: #000000;
            border: none;
            border-radius: 25px;
            padding: 12px;
            width: 100%;
            font-weight: bold;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .deposit-button:hover {
            background-color: #e0ab00;
        }

        /* Quick Access Icons */
        .quick-access {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
        }

        .quick-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 25%;
            cursor: pointer;
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
            background-color: #1e1e1e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            color: #ffffff;
            transition: transform 0.2s ease;
        }

        .quick-item:hover .icon-wrapper {
            transform: scale(1.1);
        }

        .icon-wrapper.p2p {
            background-color: #f0b90b;
            color: #000000;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .icon-wrapper.ai {
            background-color: transparent;
            border: 1px solid #4285f4;
            color: #4285f4;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .quick-item span {
            font-size: 0.75rem;
            color: #dddddd;
        }

        /* Tournament Banner */
        .tournament-banner {
            background-color: #1e1e1e;
            margin: 15px;
            padding: 15px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .tournament-info {
            display: flex;
            align-items: center;
        }

        .tournament-info h3 {
            font-size: 1rem;
            margin: 0;
            margin-right: 10px;
        }

        .gold-arrow {
            color: #f0b90b;
            background-color: #f0b90b33;
            padding: 5px;
            border-radius: 50%;
        }

        /* Trading Categories */
        .trading-categories {
            padding: 5px 15px;
        }

        .category-list {
            display: flex;
            overflow-x: auto;
            gap: 15px;
            padding: 10px 0;
            scrollbar-width: none; /* Firefox */
        }

        .category-list::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Edge */
        }

        .category {
            color: #aaaaaa;
            white-space: nowrap;
            font-size: 0.9rem;
            cursor: pointer;
            padding: 5px 0;
        }

        .category.active {
            color: #f0b90b;
            position: relative;
        }

        .category.active:after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #f0b90b;
        }

        .type-tabs {
            display: flex;
            border-bottom: 1px solid #333333;
            margin-bottom: 15px;
        }

        .type-tab {
            padding: 8px 15px;
            font-size: 0.9rem;
            color: #aaaaaa;
            cursor: pointer;
        }

        .type-tab.active {
            color: #f0b90b;
            border-bottom: 2px solid #f0b90b;
        }

        .pairs-header {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            color: #888888;
            font-size: 0.85rem;
        }

        /* Trading Pairs */
        .trading-pairs {
            margin-top: 15px;
        }

        .trading-pair-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #2a2a2a;
        }

        .pair-info {
            display: flex;
            align-items: center;
        }

        .pair-icon {
            width: 30px;
            height: 30px;
            background-color: #f3a12d;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }

        .pair-icon.eth {
            background-color: #627eea;
        }

        .pair-icon.sol {
            background-color: #14f195;
            color: #000;
            font-size: 0.7rem;
        }

        .pair-name {
            font-weight: 500;
        }

        .pair-price {
            font-weight: 500;
        }

        .pair-change {
            font-weight: 500;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .pair-change.positive {
            color: #14c784;
        }

        .pair-change.negative {
            color: #ea3943;
        }

        /* WEB3 Tab Styles */
        .web3-container {
            padding: 15px;
        }

        .section-header {
            margin-bottom: 20px;
        }

        .section-header h2 {
            margin-bottom: 5px;
            font-size: 1.3rem;
        }

        .section-header p {
            color: #aaaaaa;
            font-size: 0.9rem;
        }

        .dapp-categories {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .dapp-category {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 25%;
            color: #aaaaaa;
            cursor: pointer;
            padding: 10px 0;
        }

        .dapp-category i {
            font-size: 1.5rem;
            margin-bottom: 8px;
        }

        .dapp-category.active {
            color: #f0b90b;
        }

        .featured-dapps h3 {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .dapp-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .dapp-card {
            background-color: #1e1e1e;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .dapp-logo {
            margin-right: 15px;
        }

        .dapp-info h4 {
            margin: 0;
            font-size: 0.9rem;
        }

        .dapp-info p {
            margin: 0;
            color: #aaaaaa;
            font-size: 0.8rem;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            display: flex;
            background-color: #1e1e1e;
            padding: 10px 0;
            max-width: 480px;
            margin: 0 auto;
            z-index: 100;
            border-top: 1px solid #2a2a2a;
        }

        .nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #aaaaaa;
            font-size: 0.75rem;
            cursor: pointer;
            padding: 5px 0;
        }

        .nav-item.active {
            color: #f0b90b;
        }

        .nav-item i {
            margin-bottom: 5px;
            font-size: 1.2rem;
        }

        /* Desktop Responsive Styles */
        @media (min-width: 992px) {
            .crypto-container {
                max-width: 1200px;
                display: grid;
                grid-template-columns: 260px 1fr;
                grid-template-rows: auto 1fr;
                padding-bottom: 0;
            }

            .top-bar {
                grid-column: 1 / 3;
            }

            .main-tabs {
                margin: 15px;
                grid-column: 1 / 3;
            }

            .search-bar-container {
                grid-column: 1 / 3;
                padding: 0 15px 15px 15px;
            }

            .tab-content-container {
                grid-column: 1 / 3;
            }

            .bottom-nav {
                position: static;
                flex-direction: column;
                height: 100%;
                max-width: 260px;
                border-top: none;
                border-right: 1px solid #2a2a2a;
                padding: 30px 0;
                grid-row: 2 / 3;
                grid-column: 1 / 2;
            }

            .nav-item {
                padding: 15px 0;
                flex-direction: row;
                justify-content: flex-start;
                padding-left: 20px;
            }

            .nav-item i {
                margin-right: 15px;
                margin-bottom: 0;
            }

            .quick-access {
                grid-template-columns: repeat(4, 1fr);
                gap: 15px;
            }

            .trading-pair-item:hover {
                background-color: #2a2a2a;
            }

            .deposit-button:hover {
                background-color: #e0ab00;
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 380px) {
            .quick-item span {
                font-size: 0.7rem;
            }

            .icon-wrapper {
                width: 35px;
                height: 35px;
            }

            .deposit-header {
                font-size: 1.1rem;
            }
        }
    </style>
@endpush
