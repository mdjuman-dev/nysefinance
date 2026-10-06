@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3">
        <div class="col-12">

            <div class="card">
                <div class="card-body">

                    <ul class="nav nav-tabs coupon-nav" id="custom-content-above-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-content-above-home-tab" data-bs-toggle="pill"
                               href="#custom-content-above-home"
                               role="tab" aria-controls="custom-content-above-home" aria-selected="true">Running</a>
                        </li>

                        <li class="nav-item" style="margin-left: 10px">
                            <a class="nav-link ml-3" id="custom-content-above-win-tab" data-bs-toggle="pill"
                               href="#custom-content-above-win"
                               role="tab" aria-controls="custom-content-above-profile" aria-selected="false">Win</a>
                        </li>

                        <li class="nav-item" style="margin-left: 10px">
                            <a class="nav-link ml-3" id="custom-content-above-profile-tab" data-bs-toggle="pill"
                               href="#custom-content-above-profile"
                               role="tab" aria-controls="custom-content-above-profile" aria-selected="false">Lose</a>
                        </li>

                    </ul>


                    <div class="tab-content mt-3" id="custom-content-above-tabContent">

                        <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel"
                             aria-labelledby="custom-content-above-home-tab">
                                <div class="row justify-content-center">
                                    @if($running_coupons->isNotEmpty())
                                        @foreach($running_coupons as $running_coupon)
                                            <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                <div class="card border-0 shadow-lg rounded-4 p-3 h-100 coupon-card"
                                                     style="background: #2b2b2b; color: #fff; cursor: pointer;"
                                                     data-url="{{ route('user.coupon.details', [$running_coupon->coupon_id]) }}"
                                                     onclick="window.location=this.dataset.url">

                                                    <div class="card-body d-flex align-items-center">
                                                        <!-- Coupon Icon -->
                                                        <div class="me-3 flex-shrink-0">
                                                            <img src="{{ getImage(getFilePath('currency') .'/'.$running_coupon->coupon->icon,getFileSize('currency')) }}"
                                                                 alt="Coupon Icon"
                                                                 class="rounded-circle border border-warning"
                                                                 style="width: 55px; height: 55px; object-fit: cover;">
                                                        </div>

                                                        <!-- Coupon Info -->
                                                        <div>
                                                            <h5 class="fw-bold mb-1 text-warning">
                                                                {{ $running_coupon->coupon->name }} <small style="font-size: 11px !important;">({{$running_coupon->code}})</small>
                                                            </h5>
                                                            <div class="text-light fw-semibold mb-1">
                                                                ${{ $running_coupon->price }}
                                                            </div>
                                                            <small class="text-secondary">
                                                                Winning Date <span class="text-light">{{ $running_coupon->wining_date }}</span>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-md-12 text-center mt-4">
                                            <p class="text-light mb-3">No Coupon Available</p>
                                            <a class="btn btn-warning btn-sm rounded-pill shadow-sm"
                                               href="{{ route('user.coupons') }}">
                                                Buy Jackplay
                                            </a>
                                        </div>
                                    @endif
                                </div>
                        </div>


                        <div class="tab-pane fade" id="custom-content-above-win" role="tabpanel"
                             aria-labelledby="custom-content-above-win-tab">
                            <div class="row justify-content-center">
                                @if($win_coupons->isNotEmpty())
                                    @foreach($win_coupons as $win_coupon)
                                        <div class="col-lg-4 col-md-6 col-12 mb-4">
                                            <div class="card border-0 shadow-lg rounded-4 p-3 h-100 coupon-card"
                                                 style="background: #2b2b2b; color: #fff; cursor: pointer;"
                                                 data-url="{{ route('user.coupon.details', [$win_coupon->coupon_id]) }}"
                                                 onclick="window.location=this.dataset.url">

                                                <div class="card-body">
                                                    <h5 class="fw-bold mb-2 text-warning">
                                                        {{ $win_coupon->coupon->name }} <small style="font-size: 11px !important;">({{$win_coupon->code}})</small>
                                                    </h5>

                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="fw-semibold fs-5 text-light">
                                                            ${{ $win_coupon->price }}
                                                        </span>
                                                                                    <span class="fw-semibold fs-5 text-success">
                                                            +${{ $win_coupon->win_price }}
                                                        </span>
                                                    </div>

                                                    <div class="small text-secondary mb-2">
                                                        Winning Date
                                                        <span class="text-light">{{ $win_coupon->wining_date }}</span>
                                                        <span class="fw-bold ml-2 text-success">(WIN)</span>
                                                    </div>

                                                    @if($win_coupon->details)
                                                        <p class="small mb-0 text-light opacity-75">
                                                            {{ $win_coupon->details }}
                                                        </p>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-md-12 text-center mt-4">
                                        <p class="text-light mb-3">No Coupon Available</p>
                                        <a class="btn btn-warning btn-sm rounded-pill shadow-sm"
                                           href="{{ route('user.coupons') }}">
                                            Buy Jackplay
                                        </a>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>


                    <div class="tab-pane fade" id="custom-content-above-profile" role="tabpanel"
                         aria-labelledby="custom-content-above-profile-tab">

                        <div class="row justify-content-center">
                            @if($expired_coupons->isNotEmpty())
                                @foreach($expired_coupons as $expired_coupon)
                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                        <div class="card border-0 shadow-lg rounded-4 p-3 h-100 coupon-card expired-card"
                                             style="background: #2b2b2b; color: #fff; cursor: pointer; opacity: 0.85;"
                                             data-url="{{ route('user.coupon.details', [$expired_coupon->coupon_id]) }}"
                                             onclick="window.location=this.dataset.url">

                                            <div class="card-body d-flex align-items-center">
                                                <!-- Coupon Icon -->
                                                <div class="me-3 flex-shrink-0">
                                                    <img src="{{ getImage(getFilePath('currency') .'/'.$expired_coupon->coupon->icon,getFileSize('currency')) }}"
                                                         alt="Coupon Icon"
                                                         class="rounded-circle border border-danger"
                                                         style="width: 55px; height: 55px; object-fit: cover;">
                                                </div>

                                                <!-- Coupon Info -->
                                                <div>
                                                    <h5 class="fw-bold mb-1 text-light">
                                                        {{ $expired_coupon->coupon->name }} <small style="font-size: 11px !important;">({{$expired_coupon->code}})</small>
                                                    </h5>

                                                    <div class="text-light fw-semibold mb-1">
                                                        ${{ $expired_coupon->price }}
                                                    </div>

                                                    <small class="text-secondary">
                                                        Winning Date
                                                        <span class="text-light">{{ $expired_coupon->wining_date }}</span>
                                                        <span class="fw-bold text-danger">(EXPIRED)</span>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-12 text-center mt-4">
                                    <p class="text-light mb-3">No Coupon Available</p>
                                    <a class="btn btn-warning btn-sm rounded-pill shadow-sm"
                                       href="{{ route('user.coupons') }}">
                                        Buy Jackplay
                                    </a>
                                </div>
                            @endif
                        </div>

                        <style>
                            .coupon-card:hover {
                                transform: translateY(-5px);
                                transition: all 0.3s ease;
                                box-shadow: 0 10px 25px rgba(255, 215, 0, 0.15);
                            }
                            .expired-card:hover {
                                box-shadow: 0 10px 25px rgba(255, 0, 0, 0.25);
                            }
                        </style>

                    </div>

                </div>


            </div>
        </div>
    </div>

    <x-confirmation-modal isCustom="true"/>
@endsection

@push('topContent')
    <h4 class="mb-4">{{ __($pageTitle) }}</h4>
@endpush

@push('script-lib')

@endpush
@push('style-lib')

    <style>
        .coupon-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(255, 215, 0, 0.2);
        }

        .coupon-nav .nav-link.active {
            background: gold;
            border: 1px solid #1a1a1a !important;
            border-radius: 10px 0px 0px 10px !important;
            color: #000000 !important;
        }

        .coupon-nav .nav-link {
            padding: 6px 20px !important;
            color: white;
        }

        .coupon-nav {
            border-bottom: 1px solid #353535 !important;
        }

        .section-coupon {
            display: flex;
        }

        .section-icon img {
            border-radius: 50px;
            padding: 5px;
        }

        .section-icon {
            width: 70px;
            height: 50px;
        }

        .main-coupon-sec {
            background: #6c6c6c;
            padding: 10px;
            border-radius: 5px;
        }

        .section-details {
            color: white;
            padding-left: 10px;
        }
    </style>

@endpush


@push('script')

    <script>

        $(document).on('click', '.main-coupon-sec', function (e) {
            const url = $(this).attr('data-url');

            location.href = url;
        })

        $('#verificationType').on('change', function () {
            const type = $(this).val();

            $('.verification-sec').addClass('d-none');

            $('#' + type + '-type').removeClass('d-none');
        });

        $('#cardType').on('change', function () {
            const type = $(this).val();
            const cardLogo = $('#cardLogo');
            if (type) {
                $('#cardPreview').removeClass('hidden');
                let cardNumber = (type === 'Visa') ? '4123 **** **** 9876' : '5234 **** **** 6543';
                $('#previewNumber').text(cardNumber);

                // Set logo based on card type
                if (type === 'Visa') {
                    cardLogo.attr('src', 'https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png');
                } else {
                    cardLogo.attr('src', 'https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg');
                }
            } else {
                $('#cardPreview').addClass('hidden');
                cardLogo.attr('src', '');
            }


            if (type) {
                $('#cardPreview').removeClass('hidden');
                $('#previewType').text(type);
                let cardNumber = (type === 'Visa') ? '4123 **** **** 9876' : '5234 **** **** 6543';
                $('#previewNumber').text(cardNumber);
            } else {
                $('#cardPreview').addClass('hidden');
            }
        });


        // Update cardholder name live
        $('#fullName').on('input', function () {
            const name = $(this).val().toUpperCase() || 'CARDHOLDER NAME';
            $('#previewName').text(name);
        });

    </script>

@endpush
