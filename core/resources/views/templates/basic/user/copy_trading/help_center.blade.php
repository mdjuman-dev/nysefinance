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
        .main-section-header{
            padding-top: 20px;
            padding-right: 20px;
            padding-left: 20px;
            text-align: center;
        }
        .main-section-header span{
            font-size: 12px;
        }
        .main-section-header h6{
            font-size: 20px;
            font-weight: 600;
        }
        .input-box{
            margin-top: 22px;
            position: relative;
        }
        .input-box i{
            position: absolute;
            color: #000;
            top: 12px;
            left: 8px;
            font-size: 13px;
        }
        .input-box input{
            padding: 10px 20px 10px 30px;
            width: 100%;
            height: 38px ;
            font-size: 13px;
        }
        .input-box .form-control:focus {
            color: #131010;
            background-color: #fff;
            border-color: #131010;
            outline: 0;
            box-shadow: none !important;
        }
        .carousel-indicators [data-bs-target]{
            margin-left: 0;
            width: 15px;
            background-color: rgb(213 218 224 / 1) ;
            margin-right: 0;
        }
        .carousel-indicators .active{
            background-color: rgb(247 166 0 / 1) !important;
        }

        .help-type{
            font-size: 14px;
            padding-left: .75rem;
            padding-right: .75rem;
            margin: 5px 5px;
            height: 82px;
            position: relative;
            background-image: url('{{asset('core/public/img/bg.3b4ee08e.png')}}');
            background-position: center;
            background-size: cover;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .carousel-indicators{
            top: 100%;
        }
        .carousel-control-next-icon, .carousel-control-prev-icon {
            width: 0rem;
        }
        .help-type span{
            color: #fff;
        }
        .help-type i{
            font-size: 25px;
            color: #fff;
        }
        .socialMedia{
            margin-top: 50px;
            padding-left: 10px;
            padding-right: 10px;
        }
        body{
            background-color: #fff;
        }
        .header-section{
            background-color: #000;
        }
        .socialMedia span{
            font-weight: 600;
            font-size: 16px;
        }
        .socialMedia .View{
            font-size: 10px;
            padding: 5px 10px;
            border: 1px solid #d5dae0;
            border-radius: 8px;
            font-weight: 500;
            color: #000;
        }
        .socialMedia-link{
            padding: 10px;
            font-size: 25px;
            margin-right: 10px;
        }
        .Articles-link{
            color: #d6850d;
            font-size: 12px;
        }
        @media (min-width: 992px) {
            .header-title h6{
                font-size: 16px;
            }
            .main-section-header span {
                font-size: 18px;
            }
            .main-section-header h6 {
                font-size: 48px;
            }
            .input-box{
                display: flex;
                justify-content: center;
            }
            .input-box input {
                padding: 10px 20px 10px 40px;
                width: 45%;
                height: 48px;
                font-size: 13px;
            }
            .input-box i {
                position: absolute;
                color: #000;
                top: 15px;
                left: 29%;
                font-size: 16px;
            }
            .help-type span {
                font-size: 16px;
            }
            .socialMedia span {
                font-weight: 600;
                font-size: 25px;
            }
            .socialMedia .View {
                font-size: 13px;
                padding: 5px 15px;
            }
            .Articles-link {
                font-size: 18px;
            }
        }
        @media (min-width: 768px) {
            .header-title h6{
                font-size: 16px;
            }
        }
    </style>

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
                        <h6 class="white">Help Center</h6>
                    </div>
                    <div class="other-data"></div>
                </div>
            </div>
        </div>
    </header>

    <section class="main-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="main-section-header">
                        <span>NyseFinance Help Center</span>
                        <h6>Hello, how can we help?</h6>
                        <div class="input-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" class="form-control" placeholder="Ask Us Anything">
                        </div>
                    </div>
                    <div class="main-section-body mt-5">
                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="row pe-2 ps-2">
                                        <div class="col-6 col-lg-3 col-md-3 p-1">
                                            <div class="help-type">
                                                <span>Verify Account</span>
                                                <span><i class="fa-solid fa-shield"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-3 p-1">
                                            <div class="help-type">
                                                <span>Change Email Address</span>
                                                <span><i class="fa-solid fa-envelope"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-3 p-1">
                                            <div class="help-type">
                                                <span>Verify Account</span>
                                                <span><i class="fa-solid fa-shield"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-3 p-1">
                                            <div class="help-type">
                                                <span>Change Email Address</span>
                                                <span><i class="fa-solid fa-envelope"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="row  pe-2 ps-2">
                                        <div class="col-6 col-lg-3 col-md-3 p-1">
                                            <div class="help-type">
                                                <span>Verify Account</span>
                                                <span><i class="fa-solid fa-shield"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-3 p-1">
                                            <div class="help-type">
                                                <span>Change Email Address</span>
                                                <span><i class="fa-solid fa-envelope"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-3 p-1">
                                            <div class="help-type">
                                                <span>Verify Account</span>
                                                <span><i class="fa-solid fa-shield"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-3 p-1">
                                            <div class="help-type">
                                                <span>Change Email Address</span>
                                                <span><i class="fa-solid fa-envelope"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="row  pe-2 ps-2">
                                        <div class="col-6 col-lg-3 col-md-3 p-1">
                                            <div class="help-type">
                                                <span>Verify Account</span>
                                                <span><i class="fa-solid fa-shield"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-3 p-1">
                                            <div class="help-type">
                                                <span>Change Email Address</span>
                                                <span><i class="fa-solid fa-envelope"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-3 p-1">
                                            <div class="help-type">
                                                <span>Verify Account</span>
                                                <span><i class="fa-solid fa-shield"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-3 col-md-3 p-1">
                                            <div class="help-type">
                                                <span>Change Email Address</span>
                                                <span><i class="fa-solid fa-envelope"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                        <div class="socialMedia">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>One Click to Social Media</span>
                                <a href="#" class="View">View All <i class="fa-solid fa-angle-right ms-1"></i></a>
                            </div>
                            <div class="mt-2">
                                <div class="d-flex align-items-center">
                                    <a href="#" class="socialMedia-link">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <a href="#" class="socialMedia-link">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a href="#" class="socialMedia-link">
                                        <i class="fa-brands fa-telegram"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="socialMedia mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Top Articles</span>
                                <a href="#" class="View">View All <i class="fa-solid fa-angle-right ms-1"></i></a>
                            </div>
                            <div class="mt-4">
                                <a href="#" class="Articles-link">How to Recover Your Google Authenticator Code</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection


@push('script')

 

    <script>
        $(document).on('click', '.help-type', function (e){

            location.href='{{route('ticket.index')}}';
        })
    </script>

@endpush
