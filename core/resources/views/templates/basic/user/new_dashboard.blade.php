@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="crypto-container">
        <!-- Top Navigation Tabs -->
        <div class="main-tabs d-none">
            <div class="tab active" data-tab="exchange">Exchange</div>
            <div class="tab" data-tab="web3">WEB3</div>
        </div>

        <!-- Search and Navigation Bar -->
        <div class="search-bar-container">
            <div class="search-bar">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" class="search-text search-coin-list" placeholder="USDTB/USDT">
                <i class="fa-solid fa-qrcode qr-icon"></i>
            </div>
            <div class="show-search-coin-list d-none">

            </div>
        </div>

        <div class="section-wallet-bal">
            <div class="row">
                <div class="col-md-7 col-8">
                    @if(isset($estimatedBalance))
                        <div class="n-view-balance">
                            <p class="mb-1">Total Balance</p>
                            <div class="bal-m-sec d-none">
                                <div class="main-balance">
                                    <span
                                        class="bal-font">{{ str_replace('USD', '', showAmount($estimatedBalance)) }}</span>
                                    <span class="currency-font">USD</span> <i class="fa fa-eye hide-balance"></i>
                                </div>
                                <div class="main-balance-hide">
                                    ********* <i class="fa fa-eye-slash show-balance"></i>
                                </div>
                            </div>
                            <small class="today-pnl">Total Asset {{isset($stock_wallet)?$stock_wallet:'0.00'}}</small>
                        </div>
                    @endif
                </div>

                <div class="col-md-5 col-4">
                    <a class="deposit-button" href="{{route('user.wallet.overview',['sc'=>'tr'])}}">
                        Deposit
                    </a>
                </div>
            </div>
        </div>

        <!-- Tab Content Container -->
        <div class="tab-content-container">
            <!-- Exchange Tab Content -->
            <div class="tab-content active" id="exchange-content">
                <div class="quick-access">
                    <div class="quick-item" data-url="{{ route('trade') }}">
                        <div class="icon-wrapper">
                            <img src="{{asset('core/public/icon/classic-trade.svg')}}" alt="">
                        </div>
                        <span>Trade</span>
                    </div>

                    <div class="quick-item" data-url="{{ route('user.stock.index') }}">
                        <div class="icon-wrapper">
                            <img src="{{asset('core/public/icon/convert.svg')}}" alt="">
                        </div>
                        <span>Stocks</span>
                    </div>

                    <div class="quick-item" data-url="{{  route('user.referrals') }}">
                        <div class="icon-wrapper">
                            <img src="{{asset('core/public/icon/gold-fx.svg')}}" alt="">
                        </div>
                        <span>Referrals</span>
                    </div>

                    <div class="quick-item" data-url="{{ route('user.order.open') }}">
                        <div class="icon-wrapper">
                            <img src="{{asset('core/public/icon/pre-market-perpetuals.svg')}}" alt="">

                        </div>
                        <span>Orders</span>
                    </div>
                </div>

                <!-- Second Row of Quick Access -->
                <div class="quick-access">
                    <div class="quick-item" data-url="{{ route('user.transactions')  }}">
                        <div class="icon-wrapper">
                            <img src="{{asset('core/public/icon/buy-crypto.svg')}}" alt="">

                        </div>
                        <span>Transaction</span>
                    </div>

                    <div class="quick-item" data-url="{{ route('ticket.index')  }}">
                        <div class="icon-wrapper">
                            <img src="{{asset('core/public/icon/gold-fx.svg')}}" alt="">

                        </div>
                        <span>Support</span>
                    </div>


                    @if(checkAgent())
                        <div class="quick-item" data-url="{{ route('user.p2p.dashboard') }}">
                            <div class="icon-wrapper ai">
                                <img src="{{asset('core/public/icon/p2p-trading.svg')}}" alt="">

                            </div>
                            <span>P2P</span>
                        </div>
                    @else
                        <div class="quick-item" data-url="{{ route('p2p') }}">
                            <div class="icon-wrapper ai">
                                <img src="{{asset('core/public/icon/p2p-trading.svg')}}" alt="">

                            </div>
                            <span>P2P</span>
                        </div>
                    @endif


                    <div class="quick-item" data-url="{{route('user.more.service')}}">
                        <div class="icon-wrapper">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="6" cy="12" r="2" stroke-width="2"/>
                                <circle cx="12" cy="12" r="2" stroke-width="2"/>
                                <circle cx="18" cy="12" r="2" stroke-width="2"/>
                            </svg>
                        </div>
                        <span>More</span>
                    </div>


                </div>



                <div class="events-puzzle-section" style="display:flex;gap:14px;padding:10px 10px 0 10px;">
                    <!-- Events Carousel -->
                    <div id="eventsCarousel" class="carousel slide" data-bs-ride="carousel"
                         style="flex:1;position:relative;min-width:0;">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="event-card"
                                     style="background:#181818;border-radius:14px;padding:18px 16px 12px 16px;display:flex;flex-direction:column;justify-content:space-between;min-width:0;height:170px;box-shadow:0 2px 8px 0 #00000022;">
                                    <div style="color:#b0b0b0;font-size:0.93rem;font-weight:500;">Events</div>
                                    <div style="font-weight:700;font-size:13px;margin:10px 0 8px 0;line-height:1.3;">
                                        Combo carnival: <span style='color:#fff;'>Your Combo, your reward</span></div>
                                    <div style="display:flex;align-items:end;justify-content:end;">
                                        <img
                                            src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=facearea&w=256&h=256&facepad=2"
                                            alt="Robot 1"
                                            style="width:54px;height:54px;object-fit:cover;border-radius:50%;box-shadow:0 2px 6px #0008;">
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="event-card"
                                     style="background:#181818;border-radius:14px;padding:18px 16px 12px 16px;display:flex;flex-direction:column;justify-content:space-between;min-width:0;height:170px;box-shadow:0 2px 8px 0 #00000022;">
                                    <div style="color:#b0b0b0;font-size:0.93rem;font-weight:500;">Events</div>
                                    <div style="font-weight:700;font-size:13px;margin:10px 0 8px 0;line-height:1.3;">
                                        Trading Arena: <span style='color:#fff;'>1V1 Challenge</span></div>
                                    <div style="display:flex;align-items:end;justify-content:end;">
                                        <img
                                            src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=facearea&w=256&h=256&facepad=2"
                                            alt="Robot 2"
                                            style="width:54px;height:54px;object-fit:cover;border-radius:50%;box-shadow:0 2px 6px #0008;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            style="position:absolute;top:14px;right:22px;color:#aaa;font-size:1rem;z-index:2;font-weight:500;letter-spacing:1px;">
                            <span id="eventsCarouselIndex">1/2</span>
                        </div>
                    </div>

                    <!-- Token Splash Carousel -->
                    <div id="tokenCarousel" class="carousel slide" data-bs-ride="carousel"
                         style="flex:1;position:relative;min-width:0;">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="puzzle-card"
                                     style="background:#181818;border-radius:14px;padding:18px 16px 12px 16px;display:flex;flex-direction:column;justify-content:space-between;min-width:0;height:170px;box-shadow:0 2px 8px 0 #00000022;">
                                    <div style="color:#b0b0b0;font-size:0.93rem;font-weight:500;">Token Splash</div>
                                    <div style="display:flex;align-items:center;gap:10px;margin:10px 0 8px 0;">
                                        <img src="{{asset('core/public/icon/zeta.png')}}" alt="ZETA"
                                             style="width:36px;height:36px;border-radius:50%;background:#fff;object-fit:cover;box-shadow:0 2px 8px #0002;">
                                        <span style="font-weight:700;font-size:1.13rem;letter-spacing:1px;">ZETA</span>
                                    </div>
                                    <div style="font-size:0.98rem;color:#aaa;">Prize Pool (ZETA)</div>
                                    <div style="font-weight:700;font-size:1.13rem;color:#fff;">200,000</div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="puzzle-card"
                                     style="background:#181818;border-radius:14px;padding:18px 16px 12px 16px;display:flex;flex-direction:column;justify-content:space-between;min-width:0;height:170px;box-shadow:0 2px 8px 0 #00000022;">
                                    <div style="color:#b0b0b0;font-size:0.93rem;font-weight:500;">Token Splash</div>
                                    <div style="display:flex;align-items:center;gap:10px;margin:10px 0 8px 0;">
                                        <img src="https://assets.coingecko.com/coins/images/279/large/ethereum.png"
                                             alt="ETH"
                                             style="width:36px;height:36px;border-radius:50%;background:#fff;object-fit:cover;box-shadow:0 2px 8px #0002;">
                                        <span style="font-weight:700;font-size:1.13rem;letter-spacing:1px;">ETH</span>
                                    </div>
                                    <div style="font-size:0.98rem;color:#aaa;">Prize Pool (ETH)</div>
                                    <div style="font-weight:700;font-size:1.13rem;color:#fff;">50,000</div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="puzzle-card"
                                     style="background:#181818;border-radius:14px;padding:18px 16px 12px 16px;display:flex;flex-direction:column;justify-content:space-between;min-width:0;height:170px;box-shadow:0 2px 8px 0 #00000022;">
                                    <div style="color:#b0b0b0;font-size:0.93rem;font-weight:500;">Token Splash</div>
                                    <div style="display:flex;align-items:center;gap:10px;margin:10px 0 8px 0;">
                                        <img src="https://assets.coingecko.com/coins/images/1/large/bitcoin.png"
                                             alt="BTC"
                                             style="width:36px;height:36px;border-radius:50%;background:#fff;object-fit:cover;box-shadow:0 2px 8px #0002;">
                                        <span style="font-weight:700;font-size:1.13rem;letter-spacing:1px;">BTC</span>
                                    </div>
                                    <div style="font-size:0.98rem;color:#aaa;">Prize Pool (BTC)</div>
                                    <div style="font-weight:700;font-size:1.13rem;color:#fff;">10,000</div>
                                </div>
                            </div>
                        </div>
                        <div
                            style="position:absolute;top:14px;right:22px;color:#aaa;font-size:1rem;z-index:2;font-weight:500;letter-spacing:1px;">
                            <span id="tokenCarouselIndex">1/3</span>
                        </div>
                    </div>
                </div>



                <!-- Tournament Banner -->
                <div id="tournamentCarousel" class="carousel slide" data-bs-ride="carousel"
                     style="flex:1;position:relative;min-width:0;">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="tournament-banner"
                                 style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
                                <div class="tournament-info">
                                    <h3>NyseFinance Trading Competition</h3>
                                    <p class="tournament-desc">Win up to 100,000 USDT in prizes</p>
                                    <div class="tournament-stats">
                                        <div class="stat">
                                            <span class="stat-label">Prize Pool</span>
                                            <span class="stat-value">100,000 USDT</span>
                                        </div>
                                        <div class="stat">
                                            <span class="stat-label">Participants</span>
                                            <span class="stat-value">1,234</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="tournament-logo">
                                    <img src="{{asset('core/public/en/images/apple-touch-icon.png')}}" alt="Bybit Logo"
                                         style="width: 120px; height: auto;">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="tournament-banner"
                                 style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
                                <div class="tournament-info">
                                    <h3>NyseFinance Futures Challenge</h3>
                                    <p class="tournament-desc">Master the markets, win big rewards</p>
                                    <div class="tournament-stats">
                                        <div class="stat">
                                            <span class="stat-label">Prize Pool</span>
                                            <span class="stat-value">50,000 USDT</span>
                                        </div>
                                        <div class="stat">
                                            <span class="stat-label">Participants</span>
                                            <span class="stat-value">856</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="tournament-logo">
                                    <img src="{{asset('core/public/en/images/apple-touch-icon.png')}}" alt=""
                                         style="width: 120px; height: auto;">
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="tournament-banner"
                                 style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
                                <div class="tournament-info">
                                    <h3>NyseFinance Trading Masters</h3>
                                    <p class="tournament-desc">Show your trading skills</p>
                                    <div class="tournament-stats">
                                        <div class="stat">
                                            <span class="stat-label">Prize Pool</span>
                                            <span class="stat-value">75,000 USDT</span>
                                        </div>
                                        <div class="stat">
                                            <span class="stat-label">Participants</span>
                                            <span class="stat-value">1,567</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="tournament-logo">
                                    <img src="{{asset('core/public/en/images/apple-touch-icon.png')}}" alt="OKX Logo"
                                         style="width: 120px; height: auto;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="position:absolute;top:14px;right:22px;color:#aaa;font-size:1rem;z-index:2;font-weight:500;letter-spacing:1px;">
                        <span id="tournamentCarouselIndex">1/3</span>
                    </div>
                </div>

                <!-- Trading Categories -->

                <div class="trading-categories mt-5">
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

                    <div class="trading-pairs">
                        <!-- JS will inject content here -->
                    </div>
                </div>

            </div>

            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="view-chart-section">
                        <div class="tradingview-widget-container">
                            <div class="tradingview-widget-container__widget"></div>
                            <script type="text/javascript"
                                    src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js"
                                    async>
                                {
                                    "colorTheme"
                                :
                                    "dark",
                                        "dateRange"
                                :
                                    "12M",
                                        "showChart"
                                :
                                    false,
                                        "locale"
                                :
                                    "en",
                                        "largeChartUrl"
                                :
                                    "",
                                        "isTransparent"
                                :
                                    false,
                                        "showSymbolLogo"
                                :
                                    true,
                                        "showFloatingTooltip"
                                :
                                    false,
                                        "width"
                                :
                                    "100%",
                                        "height"
                                :
                                    "420",
                                        "tabs"
                                :
                                    [
                                        {
                                            "title": "Stocks",
                                            "symbols": [
                                                {
                                                    "s": "NASDAQ:META"
                                                },
                                                {
                                                    "s": "NASDAQ:GOOGL"
                                                },
                                                {
                                                    "s": "NASDAQ:AAPL"
                                                },
                                                {
                                                    "s": "NASDAQ:MSFT"
                                                },
                                                {
                                                    "s": "NASDAQ:AMZN"
                                                },
                                                {
                                                    "s": "NASDAQ:TSLA"
                                                },
                                                {
                                                    "s": "GPW:VISA"
                                                },
                                                {
                                                    "s": "NYSE:BABA"
                                                },
                                                {
                                                    "s": "NYSE:AGL"
                                                },
                                                {
                                                    "s": "NYSE:FVRR"
                                                },
                                                {
                                                    "s": "AMEX:BEEP"
                                                },
                                                {
                                                    "s": "FWB:JAN"
                                                },
                                                {
                                                    "s": "NASDAQ:INTC"
                                                },
                                                {
                                                    "s": "GETTEX:SUK"
                                                },
                                                {
                                                    "s": "NYSE:CAT"
                                                },
                                                {
                                                    "s": "MIL:1NKE"
                                                }
                                            ],
                                            "originalTitle": "Indices"
                                        },
                                        {
                                            "title": "Market",
                                            "symbols": []
                                        }
                                    ]
                                }
                            </script>
                        </div>
                    </div>
                </div>

            </div>


            <!-- Recent Blogs Section -->
            <!--@if(isset($recentBlogs) && $recentBlogs->count() > 0)-->
            <!--    <div class="recent-blogs-section" style="padding: 10px;">-->
            <!--        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;margin-bottom: 10px;margin-top: 10px;">-->
            <!--            <h4 style="color: #fff; font-weight: 600; margin: 0;">Recent  Posts</h4>-->

            <!--        </div>-->

            <!--        <div class="blogs-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">-->
            <!--            @foreach($recentBlogs as $blog)-->
            <!--                <div class="blog-card" data-blog-id="{{ $blog->id }}">-->
            <!--                    <div class="blog-card__header">-->
            <!--                        <div class="blog-card__user-info">-->
            <!--                            <div class="blog-card__avatar">-->
            <!--                                @if($blog->user->image)-->
            <!--                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . $blog->user->image) }}" alt="{{ $blog->user->username }}">-->
            <!--                                @else-->
            <!--                                    <div class="blog-card__avatar-placeholder">-->
            <!--                                        {{ strtoupper(substr($blog->user->username, 0, 1)) }}-->
            <!--                                    </div>-->
            <!--                                @endif-->
            <!--                            </div>-->
            <!--                            <div class="blog-card__user-details">-->
            <!--                                <h6 class="blog-card__username">{{ $blog->user->username }}</h6>-->
            <!--                                <span class="blog-card__timestamp">{{ $blog->created_at->diffForHumans() }}</span>-->
            <!--                            </div>-->
            <!--                        </div>-->
            <!--                    </div>-->

            <!--                    <div class="blog-card__content">-->
            <!--                        @if($blog->image)-->
            <!--                            <div class="blog-card__image">-->
            <!--                                <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}">-->
            <!--                            </div>-->
            <!--                        @endif-->

            <!--                        <h5 class="blog-card__title">{{ Str::limit($blog->title, 50) }}</h5>-->
            <!--                        <p class="blog-card__text">{{ Str::limit($blog->content, 200) }}</p>-->
            <!--                    </div>-->

            <!--                    <div class="blog-card__actions">-->
            <!--                        <div class="blog-card__action-group">-->
            <!--                            <button class="blog-card__action-btn like-btn" data-blog-id="{{ $blog->id }}">-->
            <!--                                <i class="fas fa-heart {{ $blog->likes > 0 ? 'text-danger' : '' }}"></i>-->
            <!--                                <span class="like-count">{{ $blog->likes }}</span>-->
            <!--                            </button>-->
            <!--                        </div>-->
            <!--                    </div>-->

                                <!-- Comments Section -->
            <!--                    <div class="blog-card__comments" id="comments-{{ $blog->id }}">-->
                                    <!-- Comment Form - Show First -->
                                    <!--<div class="comment-form">-->
                                    <!--    <div class="comment-input-group">-->
                                    <!--        <input type="text" class="comment-input" placeholder="Write a comment..." data-blog-id="{{ $blog->id }}" maxlength="200">-->
                                    <!--        <button class="comment-submit-btn" data-blog-id="{{ $blog->id }}">-->
                                    <!--            <i class="fas fa-paper-plane"></i>-->
                                    <!--        </button>-->
                                    <!--    </div>-->
                                    <!--</div>-->

                                    <!-- Existing Comments - Show Below Form -->
            <!--                        <div class="comments-list" id="comments-list-{{ $blog->id }}">-->
                                        
            <!--                                <div class="no-comments" style="text-align: center; padding: 20px; color: #888;">-->
            <!--                                    <p>No comments yet. Be the first to comment!</p>-->
            <!--                                </div>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            @endforeach-->
            <!--        </div>-->
            <!--    </div>-->
            <!--@endif-->


            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="container mt-4">
                        <h5 class="mb-0">Latest Transactions</h5>
                        <div class="table-responsive erc-table">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-dark">
                                <tr>
                                    <th>Txn Hash</th>
                                    <th>Age</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Value (ETH)</th>
                                </tr>
                                </thead>
                                <tbody id="ercTransactions" class="table-dark">

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>


            <div class="row mt-5">
                <div class="col-md-12">
                    <!-- TradingView Widget BEGIN -->
                    <div class="tradingview-widget-container">
                        <div class="tradingview-widget-container__widget"></div>

                        <script type="text/javascript"
                                src="https://s3.tradingview.com/external-embedding/embed-widget-timeline.js" async>
                            {
                                "feedMode"
                            :
                                "all_symbols",
                                    "isTransparent"
                            :
                                false,
                                    "displayMode"
                            :
                                "regular",
                                    "width"
                            :
                                "100%",
                                    "height"
                            :
                                "550",
                                    "colorTheme"
                            :
                                "dark",
                                    "locale"
                            :
                                "en"
                            }
                        </script>
                    </div>
                    <!-- TradingView Widget END -->
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

        <nav class="custom-footer-nav"
             style="position:fixed;bottom:0;left:0;right:0;max-width:480px;margin:0 auto;display:flex;background:#18181b;padding:0;z-index:100;border-top:none;height:60px;box-shadow:0 -1px 4px rgba(0,0,0,0.1);">
            <a href="{{ route('user.home') }}" class="footer-nav-item active"
               style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-decoration:none;color:#f99c26;font-size:0.8rem;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M3 10.5L12 4L21 10.5V19C21 19.8284 20.3284 20.5 19.5 20.5H4.5C3.67157 20.5 3 19.8284 3 19V10.5Z"
                        fill="#f99c26" stroke="#f99c26" stroke-width="1.5"/>
                    <rect x="9" y="15" width="6" height="2" rx="1" fill="#18181b"/>
                </svg>
                <span>Home</span>
            </a>
            <a href="{{ route('user.stock.index') }}" class="footer-nav-item"
               style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-decoration:none;color:#fff;font-size:0.8rem;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="5" y="7" width="2" height="10" rx="1" fill="#fff"/>
                    <rect x="11" y="3" width="2" height="14" rx="1" fill="#fff"/>
                    <rect x="17" y="10" width="2" height="7" rx="1" fill="#fff"/>
                </svg>
                <span>Markets</span>
            </a>
            <a href="{{ route('trade') }}" class="footer-nav-item"
               style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-decoration:none;color:#fff;font-size:0.8rem;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="11" width="16" height="2" rx="1" fill="#fff"/>
                    <path d="M8 7L4 11L8 15" stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                          stroke-linejoin="round"/>
                    <path d="M16 7L20 11L16 15" stroke="#fff" stroke-width="1.5" stroke-linecap="round"
                          stroke-linejoin="round"/>
                </svg>
                <span>Trade</span>
            </a>
            <a href="#" class="footer-nav-item"
               style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-decoration:none;color:#fff;font-size:0.8rem;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="8" stroke="#fff" stroke-width="1.5" fill="none"/>
                    <text x="8.5" y="16" font-size="8" fill="#fff">₮</text>
                    <circle cx="16.5" cy="7.5" r="2.5" fill="#18181b" stroke="#fff" stroke-width="1.2"/>
                    <path d="M16.5 6.5V8.5M15.5 7.5H17.5" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/>
                </svg>
                <span>Earn</span>
            </a>
            <a href="{{ route('user.wallet.overview') }}" class="footer-nav-item"
               style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-decoration:none;color:#fff;font-size:0.8rem;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="7" width="18" height="10" rx="2" stroke="#fff" stroke-width="1.5" fill="none"/>
                    <rect x="7" y="11" width="4" height="2" rx="1" fill="#fff"/>
                </svg>
                <span>Assets</span>
            </a>
        </nav>
    </div>
@endsection

@push('script-lib')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        const tradingPairsContainer = document.querySelector('.trading-pairs');
        let currentCategory = 'favorites';
        let interval;

        const categoryMap = {
            favorites: {
                order: 'market_cap_desc',
                ids: 'bitcoin,ethereum,solana' // You can customize your favorites here
            },
            hot: {
                order: 'market_cap_desc'
            },
            new: {
                order: 'market_cap_asc'
            },
            gainers: {
                order: 'price_change_percentage_24h_desc'
            },
            losers: {
                order: 'price_change_percentage_24h_asc'
            },
            turnover: {
                order: 'volume_desc'
            }
        };

        function setActiveTab(category) {
            const categoryElements = document.querySelectorAll('.category');
            if (categoryElements.length > 0) {
                categoryElements.forEach(el => el.classList.remove('active'));
                const activeEl = document.querySelector(`.category[data-category="${category}"]`);
                if (activeEl) activeEl.classList.add('active');
            }
        }

        async function fetchAndRenderCoins() {
            // Check if tradingPairsContainer exists before proceeding
            if (!tradingPairsContainer) {
                console.warn('Trading pairs container not found');
                return;
            }

            const params = categoryMap[currentCategory] || categoryMap['hot'];
            let url = `https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&per_page=10&page=1`;
            if (params.order) url += `&order=${params.order}`;
            if (params.ids) url += `&ids=${params.ids}`;

            tradingPairsContainer.innerHTML = '<div style="color:#f99c26;text-align:center;padding:20px;">Loading...</div>';

            try {
                const res = await fetch(url);
                const coins = await res.json();

                if (!Array.isArray(coins)) {
                    tradingPairsContainer.innerHTML = '<div style="color:#f99c26;text-align:center;">Failed to load data.</div>';
                    return;
                }

                tradingPairsContainer.innerHTML = coins.map(coin => {
                    const change = coin.price_change_percentage_24h ?? 0;
                    const positive = change >= 0;
                    const changeClass = positive ? 'positive' : 'negative';
                    return `
                <div class="trading-pair-item" data-main-coin="${coin.symbol.toUpperCase()}" data-pair="${coin.symbol.toUpperCase()}/USDT">
                    <div class="pair-info">
                        <div class="pair-icon"><img src="${coin.image}" alt="${coin.symbol}" style="width:28px;height:28px;border-radius:50%;background:#222;"></div>
                        <div class="pair-name">${coin.symbol.toUpperCase()}/USDT</div>
                    </div>
                    <div class="pair-price">${coin.current_price.toLocaleString()}</div>
                    <div class="pair-change ${changeClass}">${change.toFixed(2)}%</div>
                </div>`;
                }).join('');
            } catch (err) {
                if (tradingPairsContainer) {
                    tradingPairsContainer.innerHTML = '<div style="color:#f99c26;text-align:center;">Failed to load data.</div>';
                }
            }
        }

        function startUpdating() {
            clearInterval(interval);
            fetchAndRenderCoins();
            interval = setInterval(fetchAndRenderCoins, 30000);
        }

        // Tab click handlers
        const categoryTabs = document.querySelectorAll('.category');
        if (categoryTabs.length > 0) {
            categoryTabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const selectedCategory = tab.dataset.category;
                    if (selectedCategory !== currentCategory) {
                        currentCategory = selectedCategory;
                        setActiveTab(currentCategory);
                        startUpdating();
                    }
                });
            });
        }

        // Initial load - only if tradingPairsContainer exists
        if (tradingPairsContainer) {
            setActiveTab(currentCategory);
            startUpdating();
        } else {
            console.warn('Trading pairs container not found, skipping trading functionality');
        }
    </script>

    <script>

        $(document).ready(function () {
            ercTransactions();

            // Call every 15 seconds (15000 ms)
            setInterval(ercTransactions, 15000);
        });




        function ercTransactions(){
            $.ajax({
                type: 'GET',
                url: '{{route('erc.latest.transaction')}}',

                success: function (res) {
                    if (res.status == 'success') {
                        let html = '';

                        $.each(res.data, function (index, value) {
                            html += `
                         <tr>
                            <td>
                                <a href="${value.url}" target="_blank">
                                    ${value.trx_hash}
                                </a>
                            </td>
                            <td>
                                ${value.time}
                            </td>
                            <td>
                                ${value.from}
                            </td>
                            <td>
                                ${value.to}
                            </td>
                            <td>${value.value_amount}</td>
                        </tr>`;
                        });

                        $('#ercTransactions').html(html);
                    }
                }
            })
        }

        $(document).on('click', '.trading-pair-item', function (e){
            let tradeUrl='{{route('trade', [':sym','tg'=>'g'])}}';
            const coinName=$(this).attr('data-main-coin')+'_USDT';

            tradeUrl=tradeUrl.replace(':sym', coinName)

            location.href=tradeUrl;
        });
    </script>

@endpush

@push('style-lib')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        .dashboardBodyNav {
            display: none !important;
        }

        .dashboard-body {
            padding-top: 0px !important;
        }

        .section-wallet-bal {
            padding: 20px 10px !important;
        }

        .events-puzzle-section {
            margin-top: 20px;
        }

        .show-search-coin-list ul li {
            background: #0a171a;
            margin-top: 5px;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .show-search-coin-list {
            position: absolute;
            height: 250px;
            overflow-x: scroll;
            border-radius: 5px;
            background: #0a171a;
            width: 90%;
            z-index: 999;
        }

    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function () {
            // Top navigation tabs
            $('.tab').on('click', function () {
                const tabId = $(this).data('tab');

                // Update active tab
                $('.tab').removeClass('active');
                $(this).addClass('active');

                // Show corresponding content
                $('.tab-content').removeClass('active');
                $(`#${tabId}-content`).addClass('active');
            });

            // Trading category tabs
            $('.category').on('click', function () {
                $('.category').removeClass('active');
                $(this).addClass('active');
                // Load corresponding trading pairs (simulation for demo)
                simulateDataUpdate('category', $(this).data('category'));
            });

            // Trading type tabs (Spot/Derivatives)
            $('.type-tab').on('click', function () {
                $('.type-tab').removeClass('active');
                $(this).addClass('active');
                // Load corresponding market type (simulation for demo)
                simulateDataUpdate('type', $(this).data('type'));
            });

            // Bottom navigation
            $('.nav-item').on('click', function () {
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
            $('.quick-item').on('click', function () {
                const action = $(this).attr('data-url');

                location.href = action;
            });

            // WEB3 DApp categories
            $('.dapp-category').on('click', function () {
                $('.dapp-category').removeClass('active');
                $(this).addClass('active');
                // Load corresponding DApps (simulation for demo)
                simulateDataUpdate('dapp-category', $(this).text().trim());
            });

            // Make trading pair items clickable
            // $('.trading-pair-item').on('click', function () {
            //     const pair = $(this).data('pair');
            //     alert(`Opening trading page for ${pair}`);
            // });

            // Make DApp cards clickable
            $('.dapp-card').on('click', function () {
                const dapp = $(this).data('dapp');
                alert(`Opening ${dapp} DApp`);
            });

            // Tournament banner click
            $('.tournament-banner').on('click', function () {
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

            // Initialize tournament carousel with enhanced features
            const tournamentCarouselElement = document.getElementById('tournamentCarousel');
            if (tournamentCarouselElement && typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
                try {
                    const tournamentCarousel = new bootstrap.Carousel(tournamentCarouselElement, {
                        interval: 5000,
                        touch: true,
                        wrap: true
                    });

                    // Add smooth transition effect
                    // $('#tournamentCarousel').on('slide.bs.carousel', function () {
                    //     $(this).find('.carousel-inner').css({
                    //         'transition': 'transform 0s ease-in-out'
                    //     });
                    // });

                    // Pause carousel on hover
                    $('#tournamentCarousel').hover(
                        function () {
                            $(this).carousel('pause');
                        },
                        function () {
                            $(this).carousel('cycle');
                        }
                    );
                } catch (error) {
                    console.warn('Failed to initialize tournament carousel:', error);
                }
            } else {
                console.warn('Bootstrap or tournament carousel element not available');
            }
        });

        $(document).on('keyup or paste', '.search-coin-list', function (e) {
            const coin_name = $(this).val();

            $.ajax({
                type: 'GET',
                url: '{{route('user.get.search.coins')}}',
                data: {
                    coin_name: coin_name
                },

                success: function (res) {
                    if (res.status == 'success') {
                        let html = '';

                        $.each(res.data, function (index, value) {
                            console.log(value)
                            html += `<li class="each-coin" data-symbol="${value.symbol}">${value.symbol.replace('_', '-')}</li>`;
                        });

                        $('.show-search-coin-list').html(`<ul class="list-coin">${html}</ul>`).removeClass('d-none');

                    }
                }
            });
        });


        $(document).on('click', '.each-coin', function (e) {
            const symbol_name = $(this).attr('data-symbol');
            if (symbol_name) {
                // Generate the URL dynamically
                const url = "{{ route('trade', ['symbol' => ':symbol']) }}".replace(':symbol', symbol_name);
                location.href = url;
            }
        });


        $(document).on('click', function (event) {
            if (!$(event.target).closest('.show-search-coin-list').length) {
                $('.show-search-coin-list').addClass('d-none');
            }
        });

        // Prevent the div from closing when clicking inside it
        $(document).on('click', '.show-search-coin-list', function (event) {
            event.stopPropagation();
        });

        function isMobileDevice() {
            return /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }

        window.onload = function () {
            {{--if (!isMobileDevice()) {--}}
            {{--    location.href='{{route('user.home',['type'=>'0'])}}'--}}
            {{--}--}}
        }

        // Update carousel index display
        const eventsCarousel = document.getElementById('eventsCarousel');
        const eventsIndex = document.getElementById('eventsCarouselIndex');
        if (eventsCarousel && eventsIndex) {
            eventsCarousel.addEventListener('slid.bs.carousel', function (e) {
                eventsIndex.textContent = `${e.to + 1}/2`;
            });
        }

        const tokenCarousel = document.getElementById('tokenCarousel');
        const tokenIndex = document.getElementById('tokenCarouselIndex');
        if (tokenCarousel && tokenIndex) {
            tokenCarousel.addEventListener('slid.bs.carousel', function (e) {
                tokenIndex.textContent = `${e.to + 1}/3`;
            });
        }

        const tournamentCarousel = document.getElementById('tournamentCarousel');
        const tournamentIndex = document.getElementById('tournamentCarouselIndex');
        if (tournamentCarousel && tournamentIndex) {
            tournamentCarousel.addEventListener('slid.bs.carousel', function (e) {
                tournamentIndex.textContent = `${e.to + 1}/3`;
            });
        }

    </script>
@endpush


@push('style')
    <style>
        @media (max-width: 750px) {
            .top-for-dashboard {
                display: block !important;
            }
            .erc-table{
                overflow: scroll;
            }

            .erc-table table{
                min-width: 700px;
            }
            #ercTransactions tr td{
                font-size: 10px !important;
            }
        }


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
            background-color: #000000;
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
            padding: 10px 5px;
        }

        .search-bar {
            background-color: #373737;
            padding: 10px 15px;
            border-radius: 25px;
            display: flex;
            align-items: center;
            margin-top: 9px;
        }

        .search-icon {
            color: #ff9500;
            margin-right: 10px;
        }

        .search-text {
            flex: 1;
            background-color: #373737;
            border: hidden;
            color: white !important;
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
            background-color: #000000;
            margin: 7px;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px !important;
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
            background-color: #f99c26;
            color: #000000;
            border: none;
            float: right;
            padding: 5px 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .deposit-button:hover {
            background-color: #e0ab00;
        }

        /* Quick Access Icons */
        .quick-access {
            display: flex;
            justify-content: space-between;
            padding: 10px 5px;
        }

        .quick-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 25%;
            cursor: pointer;
        }

        .icon-wrapper svg {
            /*border: 2px solid #ffffff;*/
            border-radius: 50%;
            color: #efb90b;
            padding: 6px;
        }

        .icon-wrapper img {
            width: 100% !important;
        }

        .icon-wrapper {
            width: 32px;
            height: 32px;
            background-color: #000000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
            color: #ffffff;
            transition: transform 0.2s ease;
        }

        .quick-item:hover .icon-wrapper {
            transform: scale(1.1);
        }

        .icon-wrapper.p2p {
            background-color: #f99c26;
            color: #000000;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .icon-wrapper.ai {
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
            background-color: #000000;
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
            color: #f99c26;
            background-color: #f99c2633;
            padding: 5px;
            border-radius: 50%;
        }

        /* Trading Categories */
        .trading-categories {
            padding: 5px 15px;
            margin-top: 15px;
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
            color: #f99c26;
            position: relative;
        }

        .category.active:after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #f99c26;
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
            color: #f99c26;
            border-bottom: 2px solid #f99c26;
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
            color: #f99c26;
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
            background-color: #000000;
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
            background-color: #000000;
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
            color: #f99c26;
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

        .quick-access {
            padding-top: 5px !important;
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

        /* Tournament Carousel Styles */
        #tournamentCarousel {
            position: relative;
            margin: 10px;
            margin-top: 15px;
            height: 280px;
        }

        #tournamentCarousel .carousel-inner {
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
        }

        #tournamentCarousel .carousel-item {
            height: 100%;
        }

        #tournamentCarousel .tournament-banner {
            margin: 0;
            cursor: pointer;
            height: 280px;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
        }

        #tournamentCarousel .tournament-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 15px;
            z-index: 1;
        }

        #tournamentCarousel .tournament-info h3 {
            font-size: 1.4rem;
            margin: 0;
            color: #fff;
            font-weight: 600;
        }

        #tournamentCarousel .tournament-desc {
            color: #aaa;
            font-size: 1rem;
            margin: 0;
        }

        #tournamentCarousel .tournament-stats {
            display: flex;
            gap: 30px;
            margin-top: 10px;
        }

        #tournamentCarousel .stat {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        #tournamentCarousel .stat-label {
            color: #888;
            font-size: 0.9rem;
        }

        #tournamentCarousel .stat-value {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 500;
        }

        #tournamentCarousel .join-button {
            background: #f99c26;
            color: #000;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
            width: fit-content;
            margin-top: 10px;
        }

        #tournamentCarousel .tournament-logo {
            width: 140px;
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
        }

        #tournamentCarousel .tournament-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        #tournamentCarousel .carousel-indicators {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            margin: 0;
            padding: 0;
            display: flex;
            gap: 8px;
            z-index: 2;
        }

        #tournamentCarousel .carousel-indicators button {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.3);
            border: none;
            margin: 0;
            padding: 0;
            transition: all 0.3s ease;
        }

        #tournamentCarousel .carousel-indicators button.active {
            background-color: #f99c26;
            transform: scale(1.2);
        }

        @media (max-width: 576px) {
            #tournamentCarousel {
                height: 240px;
            }

            #tournamentCarousel .tournament-banner {
                height: 240px;
                padding: 20px;
            }

            #tournamentCarousel .tournament-info h3 {
                font-size: 1.2rem;
            }

            #tournamentCarousel .tournament-logo {
                width: 100px;
                height: 100px;
            }

            #tournamentCarousel .tournament-stats {
                gap: 20px;
            }

            #tournamentCarousel .stat-value {
                font-size: 1rem;
            }
        }

        .gold-arrow {
            color: #f99c26;
            background-color: rgba(240, 185, 11, 0.2);
            padding: 8px;
            border-radius: 50%;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .quick-item[data-url*="more.service"] .icon-wrapper svg {
            stroke: #efb90b;
            fill: #efb90b;
            width: 24px;
            height: 24px;
        }

        .quick-item[data-url*="more.service"] .icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #000000;
        }

        /* Blog Card Styles */
        .blog-card {
            background: #181818;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px 0 #00000022;
            transition: all 0.3s ease;
            max-width: 400px;
        }

        .blog-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px 0 #00000044;
        }

        .blog-card__header {
            margin-bottom: 12px;
        }

        .blog-card__user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .blog-card__avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
        }

        .blog-card__avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .blog-card__avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f99c26, #efb90b);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-weight: bold;
            font-size: 16px;
        }

        .blog-card__user-details {
            flex: 1;
        }

        .blog-card__username {
            color: #fff;
            font-weight: 600;
            margin: 0 0 3px 0;
            font-size: 13px;
        }

        .blog-card__timestamp {
            color: #888;
            font-size: 11px;
        }

        .blog-card__content {
            margin-bottom: 12px;
        }

        .blog-card__image {
            margin-bottom: 12px;
            border-radius: 8px;
            overflow: hidden;
        }

        .blog-card__image img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        .blog-card__title {
            color: #fff;
            font-weight: 600;
            margin: 0 0 8px 0;
            font-size: 15px;
            line-height: 1.3;
        }

        .blog-card__text {
            color: #ccc;
            font-size: 13px;
            line-height: 1.4;
            margin: 0;
            max-height: 3.6em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .blog-card__actions {
            border-top: 1px solid #333;
            padding-top: 16px;
        }

        .blog-card__action-group {
            display: flex;
            gap: 20px;
        }

        .blog-card__action-btn {
            background: none;
            border: none;
            color: #888;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .blog-card__action-btn:hover {
            background: rgba(249, 156, 38, 0.1);
            color: #f99c26;
        }

        .blog-card__action-btn i {
            font-size: 16px;
        }

        .like-count, .comment-count {
            font-weight: 500;
        }

        /* Comments Section */
        .blog-card__comments {
            margin-top: 16px;
            border-top: 1px solid #333;
            padding-top: 16px;
            display: none !important;
        }

        .blog-card__comments.show {
            display: block !important;
        }

        .comments-list {
            margin-bottom: 16px;
        }

        .comment-item {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            position: relative;
        }

        .comment-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
        }

        .comment-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .comment-avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f99c26, #efb90b);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-weight: bold;
            font-size: 14px;
        }

        .comment-content {
            flex: 1;
        }

        .comment-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .comment-username {
            color: #f99c26;
            font-weight: 600;
            font-size: 13px;
        }

        .comment-timestamp {
            color: #666;
            font-size: 11px;
        }

        .comment-text {
            color: #ccc;
            font-size: 13px;
            line-height: 1.4;
            margin: 0;
        }

        .comment-delete-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            color: #ff6b6b;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .comment-delete-btn:hover {
            background: rgba(255, 107, 107, 0.1);
        }

        .comment-form {
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #333;
        }

        .comment-input-group {
            display: flex;
            gap: 8px;
        }

        .comment-input {
            flex: 1;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #333;
            border-radius: 8px;
            padding: 12px 16px;
            color: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .comment-input:focus {
            outline: none;
            border-color: #f99c26;
            background: rgba(255, 255, 255, 0.15);
        }

        .comment-input::placeholder {
            color: #888;
        }

        .comment-submit-btn {
            background: #f99c26;
            border: none;
            border-radius: 8px;
            padding: 12px 16px;
            color: #000;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .comment-submit-btn:hover {
            background: #efb90b;
            transform: translateY(-1px);
        }

        .comment-submit-btn i {
            font-size: 14px;
        }

        .comments-list {
            margin-top: 16px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .blogs-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .blog-card {
                padding: 14px;
                max-width: 100%;
            }

            .blog-card__image img {
                height: 140px;
            }
        }
    </style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing blog features...');

    // Initialize blog functionality
    initializeBlogFeatures();
});

function initializeBlogFeatures() {
    console.log('Initializing blog features...');

    // Like functionality
    // document.addEventListener('click', function(e) {
    //     if (e.target.closest('.like-btn')) {
    //         e.preventDefault();
    //         const likeBtn = e.target.closest('.like-btn');
    //         const blogId = likeBtn.dataset.blogId;
    //         const likeCount = likeBtn.querySelector('.like-count');
    //         const heartIcon = likeBtn.querySelector('i');
    //
    //         console.log('Like button clicked for blog:', blogId);
    //
    //         if (!blogId) {
    //             console.error('Blog ID not found');
    //             return;
    //         }
    //
    //         // Toggle like state
    //         const isLiked = heartIcon.classList.contains('text-danger');
    //         const url = isLiked ? `/user/blog/${blogId}/unlike` : `/user/blog/${blogId}/like`;
    //
    //         fetch(url, {
    //             method: 'POST',
    //             headers: {
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    //                 'Content-Type': 'application/json',
    //             }
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 likeCount.textContent = data.likes;
    //                 if (isLiked) {
    //                     heartIcon.classList.remove('text-danger');
    //                 } else {
    //                     heartIcon.classList.add('text-danger');
    //                 }
    //                 console.log('Like updated successfully');
    //             }
    //         })
    //         .catch(error => {
    //             console.error('Error:', error);
    //             alert('Failed to update like');
    //         });
    //     }
    // });



    $(document).on('click', '.like-btn', function(e) {
        e.preventDefault();

        const likeBtn = $(this);
        const blogId = likeBtn.data('blog-id');
        const likeCount = likeBtn.find('.like-count');
        const heartIcon = likeBtn.find('svg');

        if (!blogId) {
            console.error('Blog ID not found');
            return;
        }

        if (!heartIcon.length) {
            console.error('Heart icon not found inside like button');
            return;
        }

        // Toggle like state
        const isLiked = heartIcon.hasClass('text-danger');
        const url = isLiked ? `/user/blog/${blogId}/unlike` : `/user/blog/${blogId}/like`;

        $.ajax({
            url: url,
            type: "POST",
            data:{
                '_token':'{{csrf_token()}}'
            },

            success: function(data) {
                if (data.success) {
                    if (likeCount.length) {
                        likeCount.text(data.likes);
                    }
                    heartIcon.toggleClass('text-danger', !isLiked);
                    console.log('Like updated successfully');
                }
            },
            error: function(xhr) {
                console.error("Error:", xhr.responseText);
                alert("Failed to update like");
            }
        });
    });






    // Comment toggle functionality
$(document).on('click', '.comment-btn', function(e) {
    // e.preventDefault();
    // e.stopPropagation();

    console.log('workniggg...');

    const commentBtn = $(this);
    const blogId = commentBtn.attr('data-blog-id');
    const commentsSection = $(`#comments-${blogId}`);

    console.log('Comment button clicked for blog:', blogId);
    console.log('Comments section found:', commentsSection.length > 0);

    if (commentsSection.length) {
        const isVisible = commentsSection.hasClass('show');
        console.log('Comments section currently visible:', isVisible);

        if (isVisible) {
            commentsSection.removeClass('show');
            commentBtn.css('color', '#888');
            console.log('Hiding comments section');
        } else {
            commentsSection.addClass('show');
            commentBtn.css('color', '#f99c26');
            console.log('Showing comments section');
        }
    } else {
        console.error('Comments section not found for blog ID:', blogId);
        alert('Comments section not found');
    }
});

// Add comment functionality
$(document).on('click', '.comment-submit-btn', function(e) {
    e.preventDefault();
    const submitBtn = $(this);
    const blogId = submitBtn.data('blog-id');
    const commentInput = $(`.comment-input[data-blog-id="${blogId}"]`);

    console.log('Comment submit clicked for blog:', blogId);

    if (!blogId || !commentInput.length) {
        console.error('Blog ID or comment input not found');
        return;
    }

    const commentText = commentInput.val().trim();
    if (!commentText) {
        alert('Please enter a comment');
        return;
    }

    // Show loading state
    submitBtn.prop('disabled', true);
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i>');

    $.ajax({
        url: `/user/blog/${blogId}/comment`,
        method: 'POST',
        data:{
            comment: commentText,'_token':'{{csrf_token()}}'
        },

        success: function(data) {
            if (data.success) {
                // Add comment to the list
                addCommentToDOM(blogId, data.comment);
                commentInput.val('');

                // Update comment count
                const commentCount = $(`.comment-btn[data-blog-id="${blogId}"] .comment-count`);
                if (commentCount.length) {
                    commentCount.text(parseInt(commentCount.text()) + 1);
                }

                console.log('Comment added successfully');
            } else {
                alert('Failed to add comment: ' + (data.message || 'Unknown error'));
            }
        },
        error: function(error) {
            console.error('Error:', error);
            alert('Error adding comment');
        },
        complete: function() {
            // Reset button state
            submitBtn.prop('disabled', false);
            submitBtn.html('<i class="fas fa-paper-plane"></i>');
        }
    });
});

// Note: You'll need to keep your addCommentToDOM function as is since it wasn't provided in the original code


    // // Comment toggle functionality
    // document.addEventListener('click', function(e) {
    //     if (e.target.closest('.comment-btn')) {
    //         e.preventDefault();
    //         e.stopPropagation();
    //         const commentBtn = e.target.closest('.comment-btn');
    //         const blogId = commentBtn.dataset.blogId;
    //         const commentsSection = document.getElementById(`comments-${blogId}`);

    //         console.log('Comment button clicked for blog:', blogId);
    //         console.log('Comments section found:', commentsSection);

    //         if (commentsSection) {
    //             const isVisible = commentsSection.classList.contains('show');
    //             console.log('Comments section currently visible:', isVisible);

    //             if (isVisible) {
    //                 commentsSection.classList.remove('show');
    //                 commentBtn.style.color = '#888';
    //                 console.log('Hiding comments section');
    //             } else {
    //                 commentsSection.classList.add('show');
    //                 commentBtn.style.color = '#f99c26';
    //                 console.log('Showing comments section');
    //             }
    //         } else {
    //             console.error('Comments section not found for blog ID:', blogId);
    //             alert('Comments section not found');
    //         }
    //     }
    // });

    // // Add comment functionality
    // document.addEventListener('click', function(e) {
    //     if (e.target.closest('.comment-submit-btn')) {
    //         e.preventDefault();
    //         const submitBtn = e.target.closest('.comment-submit-btn');
    //         const blogId = submitBtn.dataset.blogId;
    //         const commentInput = document.querySelector(`.comment-input[data-blog-id="${blogId}"]`);

    //         console.log('Comment submit clicked for blog:', blogId);

    //         if (!blogId || !commentInput) {
    //             console.error('Blog ID or comment input not found');
    //             return;
    //         }

    //         const commentText = commentInput.value.trim();
    //         if (!commentText) {
    //             alert('Please enter a comment');
    //             return;
    //         }

    //         // Show loading state
    //         submitBtn.disabled = true;
    //         submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    //         fetch(`/user/blog/${blogId}/comment`, {
    //             method: 'POST',
    //             headers: {
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    //                 'Content-Type': 'application/json',
    //             },
    //             body: JSON.stringify({
    //                 comment: commentText
    //             })
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 // Add comment to the list
    //                 addCommentToDOM(blogId, data.comment);
    //                 commentInput.value = '';

    //                 // Update comment count
    //                 const commentCount = document.querySelector(`.comment-btn[data-blog-id="${blogId}"] .comment-count`);
    //                 if (commentCount) {
    //                     commentCount.textContent = parseInt(commentCount.textContent) + 1;
    //                 }

    //                 console.log('Comment added successfully');
    //             } else {
    //                 alert('Failed to add comment: ' + (data.message || 'Unknown error'));
    //             }
    //         })
    //         .catch(error => {
    //             console.error('Error:', error);
    //             alert('Error adding comment');
    //         })
    //         .finally(() => {
    //             // Reset button state
    //             submitBtn.disabled = false;
    //             submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
    //         });
    //     }
    // });

    // Delete comment functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.comment-delete-btn')) {
            e.preventDefault();
            const deleteBtn = e.target.closest('.comment-delete-btn');
            const commentId = deleteBtn.dataset.commentId;
            const commentItem = deleteBtn.closest('.comment-item');
            const blogId = commentItem.closest('.blog-card').dataset.blogId;

            if (confirm('Are you sure you want to delete this comment?')) {
                fetch(`/user/blog/comment/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        commentItem.remove();

                        // Update comment count
                        const commentCount = document.querySelector(`.comment-btn[data-blog-id="${blogId}"] .comment-count`);
                        if (commentCount) {
                            commentCount.textContent = parseInt(commentCount.textContent) - 1;
                        }

                        console.log('Comment deleted successfully');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting comment');
                });
            }
        }
    });
}

function addCommentToDOM(blogId, commentData) {
    const commentsList = document.getElementById(`comments-list-${blogId}`);

    if (!commentsList) {
        console.error('Comments list not found for blog ID:', blogId);
        return;
    }

    // Remove "no comments" message if it exists
    const noComments = commentsList.querySelector('.no-comments');
    if (noComments) {
        noComments.remove();
    }

    const commentItem = document.createElement('div');
    commentItem.className = 'comment-item';
    commentItem.dataset.commentId = commentData.id;

    const avatar = commentData.user.image ?
        `<img src="${commentData.user.image}" alt="${commentData.user.username}">` :
        `<div class="comment-avatar-placeholder">${commentData.user.username.charAt(0).toUpperCase()}</div>`;

    commentItem.innerHTML = `
        <div class="comment-avatar">
            ${avatar}
        </div>
        <div class="comment-content">
            <div class="comment-header">
                <span class="comment-username">${commentData.user.username}</span>
                <span class="comment-timestamp">Just now</span>
            </div>
            <p class="comment-text">${commentData.comment}</p>
            <button class="comment-delete-btn" data-comment-id="${commentData.id}">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;

    commentsList.appendChild(commentItem);
}
</script>
@endpush
