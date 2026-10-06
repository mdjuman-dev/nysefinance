@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="mb-4">Services</h2>

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Search</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-muted mb-3">My Favorites</h5>

                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Recommended</h6>
                            <div class="row g-2">
                                <div class="col-6 col-md-3">
                                    <button class="btn btn-outline-secondary w-100 text-start">Buy Crypto</button>
                                </div>
                                <div class="col-6 col-md-3">
                                    <button class="btn btn-outline-secondary w-100 text-start">Trade</button>
                                </div>
                                <div class="col-6 col-md-3">
                                    <button class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center">
                                        Spot <i class="fas fa-chevron-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6 col-md-3">
                                <button class="btn btn-outline-secondary w-100 text-start">Margin Staked SOL</button>
                            </div>
                            <div class="col-6 col-md-3">
                                <button class="btn btn-outline-secondary w-100 text-start">Invite Friends</button>
                            </div>
                            <div class="col-6 col-md-3">
                                <button class="btn btn-outline-secondary w-100 text-start">Rewards Hub</button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Buy Crypto</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6 col-md-3">
                                <button class="btn btn-outline-secondary w-100 text-start">Deposit</button>
                            </div>
                            <div class="col-6 col-md-3">
                                <button class="btn btn-outline-secondary w-100 text-start">Buy Crypto</button>
                            </div>
                            <div class="col-6 col-md-3">
                                <button class="btn btn-outline-secondary w-100 text-start">P2P Trading</button>
                            </div>
                            <div class="col-6 col-md-3">
                                <button class="btn btn-outline-secondary w-100 text-start">Fiat Deposit</button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Trade</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Convert</h6>
                            <div class="row g-2">
                                <div class="col-6 col-md-3">
                                    <button class="btn btn-outline-secondary w-100 text-start">TradingBot</button>
                                </div>
                                <div class="col-6 col-md-3">
                                    <button class="btn btn-outline-secondary w-100 text-start">Copy Trading</button>
                                </div>
                                <div class="col-6 col-md-3">
                                    <button class="btn btn-outline-secondary w-100 text-start">Gold & FX</button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h6 class="text-muted mb-2">Pre-Market Trading</h6>
                            <div class="row g-2">
                                <div class="col-6 col-md-3">
                                    <button class="btn btn-outline-secondary w-100 text-start">Pre-Market Perpetuals</button>
                                </div>
                                <div class="col-6 col-md-3">
                                    <button class="btn btn-outline-secondary w-100 text-start">OTC Trading</button>
                                </div>
                                <div class="col-6 col-md-3">
                                    <button class="btn btn-outline-secondary w-100 text-start">Demo Trading</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="card">
                    <div class="card-header bg-light">
                        <h4 class="mb-0">Spot X</h4>
                    </div>
                    <div class="card-body">
                        <!-- Spot X content would go here -->
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
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
            border-bottom: none;
        }

        .btn-outline-secondary {
            border-color: #e9ecef;
            color: #495057;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
        }

        hr {
            border-top: 1px solid #e9ecef;
        }

        @media (max-width: 767.98px) {
            .card-body .row > div {
                margin-bottom: 10px;
            }
        }
    </style>
@endpush
