@extends($activeTemplate . 'layouts.master')

@push('topContent')

@endpush
@push('ip-css')
    <link rel="stylesheet" href="{{asset('core/public/style.css')}}">

    <style>

        .icon{
            font-size: 13px;
            color: #fff;
        }
        .services-text{
            font-size: 10px;
            font-weight: 500;
            color: #fff;
        }
        .services-title{
            font-size: 11px;
            margin: 15px 0;
            color: #636060;
        }
        .other-data i{
            font-size: 13px;
            font-weight: 500;
            color: #fff;
        }
        body{
            background-color: #fff;
        }
        .header-section{
            background-color: black;
        }
        .navbar-toggler:focus{
            box-shadow: none;
        }
        .navbar-light .navbar-toggler{
            border: none !important;

        }
        .border-line{
            border-bottom: 1px solid #e2e2e2;
        }
        .text-center-modli{
            text-align: center;
            margin-top: 20px;
        }
        .text-center-modli h3{
            font-size: 30px;
            color: #f7a600;
            font-weight: 700;
        }
        .text-center-modli p {
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            text-align: center;
            color: #81858c;
            margin: 16px 0 32px;
        }
        .btn-join{
            width: 100%;
            font-weight: 700;
        }
        .other-title{
            color: #ffffff;
            font-size: 13px;
        }
        .con-box{
            border: 1px solid #4b4b4b;
            border-radius: 5px;
        }
        @media (min-width: 992px) {
            .header-title h6{
                font-size: 16px;
            }
            .text-center-modli {
                text-align: left;
                margin-top: 0px;
            }
            .text-center-modli p {
                font-size: 24px;
                text-align: left;
            }
            .text-center-modli h3 {
                font-size: 48px;
                color: #000;
            }
            .btn-join {
                width: 50%;
            }
        }
        @media (min-width: 768px) {
            .header-title h6{
                font-size: 16px;
            }
            .text-center-modli {
                text-align: left;
                margin-top: 0px;
            }
            .text-center-modli p {
                font-size: 16px;
                text-align: left;
            }
            .text-center-modli h3 {
                font-size: 25px;
                color: #000;
            }
            .btn-join {
                font-size: 14px;
                width: 60%;
            }
        }
    </style>



    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"  />
@endpush

@section('content')


    <header class="header-section">
        <div class="container">
            <div class="header-section-content">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="right-back-btn">
                        <a href="{{route('user.home')}}" class="right-back-action">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="header-title">
                        <h6 class="white">Affiliates</h6>
                    </div>
                    <div class="other-data">
                    </div>
                </div>
            </div>
        </div>
    </header>


    <section class="main-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 border-line">
                    <nav class="navbar navbar-expand-lg navbar-light ">
                        <div class="container-fluid">
                            <a class="navbar-brand" href="#">AFFILIATES</a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Link</a>
                                    </li>
                                </ul>
                                <button class="btn btn-outline-info" type="button">Login</button>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="col-12 mt-5">
                    <div class="row align-items-center flex-lg-row-reverse flex-md-row-reverse">
                        <div class="col-12 col-lg-6 col-md-6">
                            <img src="{{asset('core/public/img/dashborad-img.svg')}}" alt="" class="img-fluid">
                        </div>
                        <div class="col-12 col-lg-6 col-md-6 text-center-modli">
                            <h3>Boost your Earnings through NyseFinance’s Affiliate Program</h3>
                            <p>Monetize your influence. Grow through robust analytics. Join a tight-knit community.</p>
                            <button type="button" class="btn btn-warning btn-join copyTextBtn clickCopy">Join our Affiliate Program</button>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-5">
                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-4 col-md-6 con-box">
                            <div class="mt-2 mb-2 p-2 text-center">
                                <img src="{{asset('core/public/img/EN_2503-T50347_MT5_Indices_0-Fee_NoCTA_1600x900.png')}}" alt="" class="img-fluid">
                                <span class="other-title">Global Crypto Conferernces</span>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 col-md-6 con-box mt-3">
                            <div class="mt-2 mb-2 p-2 text-center">
                                <img src="{{asset('core/public/img/EN_2503-T50347_MT5_Indices_0-Fee_NoCTA_1600x900.png')}}" alt="" class="img-fluid">
                                <span class="other-title">F1 Redbull Racing VIP Passes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection


@push('script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).on('click', '.clickCopy', function (e){
            e.preventDefault();

            toastr.success('Invite URL Successfully Copied To Clickboard', 'Copied!')
        })
    </script>

@endpush
