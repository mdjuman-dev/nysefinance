@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="container">
        <div class="header">
            <a href="#" class="me-3">
                <i class="fas fa-arrow-left text-white"></i>
            </a>
            <h1 class="m-0">Services</h1>
        </div>

        <div class="search-container">
            <div class="input-group">
        <span class="input-group-text bg-dark border-0 text-secondary">
          <i class="fas fa-search"></i>
        </span>
                <input type="text" class="search-bar" placeholder="Search">
            </div>
        </div>

        <div class="section-title">My Favorites</div>

        <div class="favorites">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="favorites-icons">
                    <div class="favorite-item">
                        <div class="icon-circle">
                            <i class="fas fa-wallet text-warning"></i>
                        </div>
                    </div>
                    <div class="favorite-item">
                        <div class="icon-circle">
                            <img src="{{ asset('assets/images/placeholder.png') }}" alt="P2P" class="rounded-circle bg-warning" width="20" height="20">
                        </div>
                    </div>
                    <div class="favorite-item">
                        <div class="icon-circle">
                            <i class="fas fa-credit-card text-light"></i>
                        </div>
                    </div>
                    <div class="favorite-item">
                        <div class="icon-circle">
                            <i class="fas fa-gift text-light"></i>
                        </div>
                    </div>
                    <div class="favorite-item">
                        <div class="icon-circle">
                            <i class="fas fa-coins text-warning"></i>
                        </div>
                    </div>
                    <div class="favorite-item">
                        <div class="icon-circle">
                            <i class="far fa-calendar text-light"></i>
                        </div>
                    </div>
                    <div class="favorite-item">
                        <div class="icon-circle">
                            <i class="fas fa-folder text-warning"></i>
                        </div>
                    </div>
                </div>
                <button class="edit-button">Edit</button>
            </div>
        </div>

        <div class="tab-menu">
            <div class="tab-item active">Recommended</div>
            <div class="tab-item">Buy Crypto</div>
            <div class="tab-item">Trade</div>
            <div class="tab-item">Spot X</div>
        </div>

        <div class="service-grid">
            <div class="service-item">
                <div class="service-icon position-relative">
                    <i class="fas fa-sync text-warning"></i>
                    <span class="service-tag">NEW</span>
                </div>
                <div class="service-name">Margin <br>Staked SOL</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-user-plus text-light"></i>
                </div>
                <div class="service-name">Invite <br>Friends</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-gift text-light"></i>
                </div>
                <div class="service-name">Rewards Hub</div>
            </div>
        </div>

        <div class="section-header">Buy Crypto</div>

        <div class="service-grid">
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-wallet text-light"></i>
                </div>
                <div class="service-name">Deposit</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-shopping-cart text-light"></i>
                </div>
                <div class="service-name">Buy Crypto</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <img src="{{ asset('assets/images/placeholder.png') }}" alt="P2P" class="rounded-circle bg-warning" width="20" height="20">
                </div>
                <div class="service-name">P2P Trading</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-dollar-sign text-light"></i>
                </div>
                <div class="service-name">Fiat Deposit</div>
            </div>
        </div>

        <div class="section-header">Trade</div>

        <div class="service-grid">
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-exchange-alt text-warning"></i>
                </div>
                <div class="service-name">Convert</div>
            </div>
            <div class="service-item">
                <div class="service-icon position-relative">
                    <i class="fas fa-robot text-light"></i>
                    <span class="service-tag">HOT</span>
                </div>
                <div class="service-name">TradingBot</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-clone text-warning"></i>
                </div>
                <div class="service-name">Copy Trading</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <div class="position-relative">
                        <span class="bg-warning px-1 rounded" style="font-size: 8px; font-weight: bold;">MT5</span>
                    </div>
                </div>
                <div class="service-name">Gold & FX</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-puzzle-piece text-warning"></i>
                </div>
                <div class="service-name">Classic Trade</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-gem text-warning"></i>
                </div>
                <div class="service-name">Gold-FX</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-coins text-warning"></i>
                </div>
                <div class="service-name">OTC Trade</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-puzzle-piece text-warning"></i>
                </div>
                <div class="service-name">Puzzle Hunt</div>
            </div>
        </div>

        <div class="section-header">Spot X</div>

        <div class="service-grid mb-5">
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-chart-line text-warning"></i>
                </div>
                <div class="service-name">Spot X</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-token text-warning"></i>
                </div>
                <div class="service-name">Token Splash</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-vote-yea text-warning"></i>
                </div>
                <div class="service-name">By Votes</div>
            </div>
        </div>

        <div class="section-header">Rewards & Games</div>

        <div class="service-grid mb-5">
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-gift text-warning"></i>
                </div>
                <div class="service-name">Reward Hub</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-treasure-chest text-warning"></i>
                </div>
                <div class="service-name">Treasure Hunt</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-trophy text-warning"></i>
                </div>
                <div class="service-name">Leader Board</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-rocket text-warning"></i>
                </div>
                <div class="service-name">LaunchPool</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-rocket text-warning"></i>
                </div>
                <div class="service-name">LaunchPad</div>
            </div>
            <div class="service-item">
                <div class="service-icon">
                    <i class="fas fa-history text-warning"></i>
                </div>
                <div class="service-name">Reward History</div>
            </div>
        </div>
    </div>

    <div class="bottom-nav">
        <div class="bottom-nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </div>
        <div class="bottom-nav-item">
            <i class="fas fa-chart-line"></i>
            <span>Markets</span>
        </div>
        <div class="bottom-nav-item">
            <i class="far fa-square"></i>
            <span>Trade</span>
        </div>
        <div class="bottom-nav-item">
            <i class="fas fa-chevron-down"></i>
            <span>More</span>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
@endpush

@push('style')
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .header {
            padding: 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #2a2a2a;
        }

        .search-bar {
            background-color: #2a2a2a;
            border-radius: 20px;
            padding: 10px 15px;
            margin: 15px 0;
            width: 100%;
            color: #8a8a8a;
            border: none;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 500;
            margin: 15px 0;
            color: #e0e0e0;
        }

        .favorites {
            background-color: #1e1e1e;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .favorites-icons {
            display: flex;
            justify-content: space-between;
        }

        .favorite-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-right: 15px;
        }

        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #2a2a2a;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 5px;
        }

        .icon-circle img {
            width: 20px;
            height: 20px;
        }

        .tab-menu {
            display: flex;
            border-bottom: 1px solid #2a2a2a;
            margin-bottom: 20px;
        }

        .tab-item {
            padding: 10px 15px;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .tab-item.active {
            color: white;
            border-bottom: 2px solid #F0B90B;
        }

        .tab-item:not(.active) {
            color: #8a8a8a;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .service-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .service-icon {
            width: 50px;
            height: 50px;
            background-color: #1e1e1e;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 8px;
        }

        .service-name {
            font-size: 0.8rem;
            color: #e0e0e0;
        }

        .service-tag {
            background-color: #F0B90B;
            color: black;
            font-size: 0.6rem;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 4px;
            position: absolute;
            top: -5px;
            right: -5px;
        }

        .section-header {
            font-size: 1rem;
            color: #8a8a8a;
            margin: 10px 0;
        }

        .edit-button {
            background-color: #F0B90B;
            color: black;
            border: none;
            border-radius: 6px;
            padding: 5px 15px;
            font-weight: 500;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #1e1e1e;
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            border-top: 1px solid #2a2a2a;
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #8a8a8a;
            font-size: 0.7rem;
        }

        .position-relative {
            position: relative;
        }

        @media (min-width: 768px) {
            .container {
                max-width: 720px;
                margin: 0 auto;
            }

            .service-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }
    </style>
@endpush

@push('script')
    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabItems = document.querySelectorAll('.tab-item');

            tabItems.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabItems.forEach(function(t) {
                        t.classList.remove('active');
                    });

                    // Add active class to clicked tab
                    this.classList.add('active');
                });
            });
        });
    </script>
@endpush
