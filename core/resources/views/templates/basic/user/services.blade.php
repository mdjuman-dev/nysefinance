@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="services-container bg-dark text-white py-4">
    <div class="container">
        <!-- Header with Back Button -->
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('user.home') }}" class="text-white me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h3 class="mb-0">Services</h3>
        </div>

        <!-- Search Bar -->
        <div class="search-bar mb-4">
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary">
                    <i class="fas fa-search text-secondary"></i>
                </span>
                <input type="text" class="form-control bg-dark border-secondary text-white" placeholder="Search services...">
            </div>
        </div>

        <!-- My Favorites Section -->
        <div class="section mb-4">
            <h5 class="mb-3">My Favorites</h5>
            <div class="favorites-container p-3 bg-dark-secondary rounded-3 mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="favorite-icons">
                        <span class="favorite-icon"><i class="fas fa-wallet"></i></span>
                        <span class="favorite-icon"><i class="fas fa-coins"></i></span>
                        <span class="favorite-icon"><i class="fas fa-exchange-alt"></i></span>
                        <span class="favorite-icon"><i class="fas fa-gift"></i></span>
                        <span class="favorite-icon"><i class="fas fa-dollar-sign"></i></span>
                        <span class="favorite-icon"><i class="far fa-calendar"></i></span>
                        <span class="favorite-icon"><i class="fas fa-chart-line"></i></span>
                    </div>
                    <button class="btn btn-warning btn-sm">Edit</button>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="nav-tabs-wrapper mb-4">
            <ul class="nav nav-tabs border-0" id="servicesTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="recommended-tab" data-bs-toggle="tab" data-bs-target="#recommended" type="button" role="tab" aria-controls="recommended" aria-selected="true">Recommended</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="buy-crypto-tab" data-bs-toggle="tab" data-bs-target="#buy-crypto" type="button" role="tab" aria-controls="buy-crypto" aria-selected="false">Buy Crypto</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="trade-tab" data-bs-toggle="tab" data-bs-target="#trade" type="button" role="tab" aria-controls="trade" aria-selected="false">Trade</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="spot-tab" data-bs-toggle="tab" data-bs-target="#spot" type="button" role="tab" aria-controls="spot" aria-selected="false">Spot</button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="servicesTabContent">
            <!-- Recommended Tab -->
            <div class="tab-pane fade show active" id="recommended" role="tabpanel" aria-labelledby="recommended-tab">
                <div class="row g-3">
                    <div class="col-6 col-md-4">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Margin</h6>
                                    <small class="text-muted">Staked SOL</small>
                                    <span class="badge bg-warning text-dark ms-2">NEW</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Invite</h6>
                                    <small class="text-muted">Friends</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-gift"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Rewards Hub</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buy Crypto Tab -->
            <div class="tab-pane fade" id="buy-crypto" role="tabpanel" aria-labelledby="buy-crypto-tab">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Deposit</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Buy Crypto</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">P2P Trading</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Fiat Deposit</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trade Tab -->
            <div class="tab-pane fade" id="trade" role="tabpanel" aria-labelledby="trade-tab">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">TradingBot</h6>
                                    <span class="badge bg-warning text-dark">HOT</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-copy"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Copy Trading</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Gold & FX</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spot Tab -->
            <div class="tab-pane fade" id="spot" role="tabpanel" aria-labelledby="spot-tab">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="far fa-clock"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Pre-Market Trading</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-infinity"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Pre-Market Perpetuals</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">OTC Trading</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="service-card">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper">
                                    <i class="fas fa-desktop"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0">Demo Trading</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style-lib')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@push('style')
<style>
    :root {
        --primary-color: #ffc107;
        --primary-hover: #e0a800;
        --dark-bg: #1a1a1a;
        --dark-secondary: #2a2a2a;
        --dark-tertiary: #333;
        --text-muted: #6c757d;
        --text-white: #ffffff;
        --border-radius: 12px;
        --transition: all 0.3s ease;
    }

    .services-container {
        min-height: 100vh;
        background-color: var(--dark-bg);
    }

    .bg-dark-secondary {
        background-color: var(--dark-secondary);
    }

    /* Search Bar Styles */
    .search-bar .form-control {
        height: 50px;
        background-color: var(--dark-secondary) !important;
        border-color: var(--dark-tertiary);
        color: var(--text-white);
    }

    .search-bar .form-control:focus {
        box-shadow: none;
        border-color: var(--primary-color);
    }

    .search-bar .input-group-text {
        background-color: var(--dark-secondary);
        border-color: var(--dark-tertiary);
        border-right: none;
    }

    /* Favorites Section */
    .favorite-icons {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding-bottom: 5px;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .favorite-icons::-webkit-scrollbar {
        display: none;
    }

    .favorite-icon {
        width: 40px;
        height: 40px;
        background-color: var(--dark-tertiary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        transition: var(--transition);
        flex-shrink: 0;
    }

    .favorite-icon:hover {
        background-color: var(--primary-color);
        color: var(--dark-bg);
        transform: scale(1.1);
    }

    /* Navigation Tabs */
    .nav-tabs {
        border: none;
        overflow-x: auto;
        flex-wrap: nowrap;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .nav-tabs::-webkit-scrollbar {
        display: none;
    }

    .nav-tabs .nav-link {
        color: var(--text-muted);
        border: none;
        padding: 10px 20px;
        margin-right: 10px;
        border-radius: 20px;
        transition: var(--transition);
        position: relative;
        white-space: nowrap;
    }

    .nav-tabs .nav-link:hover {
        color: var(--primary-color);
    }

    .nav-tabs .nav-link.active {
        background-color: transparent;
        color: var(--primary-color);
    }

    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: var(--primary-color);
        border-radius: 2px;
    }

    /* Service Cards */
    .service-card {
        background-color: var(--dark-secondary);
        border-radius: var(--border-radius);
        padding: 15px;
        transition: var(--transition);
        cursor: pointer;
        border: 1px solid transparent;
    }

    .service-card:hover {
        background-color: var(--dark-tertiary);
        transform: translateY(-2px);
        border-color: var(--primary-color);
    }

    .icon-wrapper {
        width: 40px;
        height: 40px;
        background-color: var(--dark-tertiary);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        transition: var(--transition);
    }

    .service-card:hover .icon-wrapper {
        background-color: var(--primary-color);
        color: var(--dark-bg);
    }

    /* Badges and Buttons */
    .badge {
        font-size: 0.7rem;
        padding: 0.35em 0.65em;
        font-weight: 500;
    }

    .btn-warning {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: var(--dark-bg);
        transition: var(--transition);
    }

    .btn-warning:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        transform: translateY(-1px);
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .favorite-icon {
            width: 35px;
            height: 35px;
        }

        .nav-tabs .nav-link {
            padding: 8px 15px;
            font-size: 0.9rem;
        }

        .service-card {
            padding: 12px;
        }

        .icon-wrapper {
            width: 35px;
            height: 35px;
        }

        h6 {
            font-size: 0.9rem;
        }

        small {
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";

        // Initialize Bootstrap tabs
        document.addEventListener('DOMContentLoaded', function() {
            var triggerTabList = [].slice.call(document.querySelectorAll('#servicesTabs button'));
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl);

                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault();
                    tabTrigger.show();
                });
            });
        });

        // Search functionality
        const searchInput = document.querySelector('.search-bar input');
        const serviceCards = document.querySelectorAll('.service-card');

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();

            serviceCards.forEach(card => {
                const title = card.querySelector('h6').textContent.toLowerCase();
                const small = card.querySelector('small')?.textContent.toLowerCase() || '';

                if (title.includes(searchTerm) || small.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Add smooth scrolling to favorite icons container
        $('.favorite-icons').on('wheel', function(e) {
            if (this.scrollWidth > this.clientWidth) {
                e.preventDefault();
                this.scrollLeft += e.deltaY;
            }
        });

        // Add click handlers for service cards
        $('.service-card').on('click', function() {
            // Add your click handling logic here
            const serviceName = $(this).find('h6').text();
            console.log('Service clicked:', serviceName);
        });

    })(jQuery);
</script>
@endpush
