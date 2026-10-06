@extends($activeTemplate . 'layouts.master')

@push('topContent')

@endpush
@push('ip-css')
    <link rel="stylesheet" href="{{asset('core/public/style.css')}}">

    <style>
        .trading-classic-content-box-header-left .img img{
            width: 30px;
            height: 30px;
            border-radius: 50px;
        }
        .texts{
            color: #fff;
            font-size: 14px;
        }
        .trading-classic-content-box-header-left{
            display: flex;
            align-items: center;
        }
        .trading-classic-content-box-main .text{
            font-size: 15px;
            color: #808284;
        }
        .trading-classic-content-box-main .price{
            font-weight: 600;
        }
        .trading-classic-content-box-footer{
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .trading-classic-content-box-footer .text{
            font-size: 15px;
            color: #bbbbbb;
        }
        .trading-classic-content-box-footer .price{
            font-size: 11px;
            color: #fff;
            font-weight: 600;
        }
        .btn-edit {
            background-color: #FFB200;
            color: #000;
            font-weight: 600;
            padding: 7px 17px !important;
            font-size: 12px;
        }
        @media (min-width: 992px) {
            .owl-nav-link {
                font-size: 16px;
                padding: 10px 15px;
            }
            .trading-classic-type {
                font-size: 16px;
            }
            .other-data-war {
                font-size: 16px;
            }
            .trading-classic-content-box-header-left .img img {
                width: 50px;
                height: 50px;
            }
            .texts {
                font-size: 16px;
            }
            .star {
                font-size: 16px;
                margin-right: 10px;
            }
            .btn-edit {
                padding: 5px 10px !important;
                font-size: 13px;
            }
            .trading-classic-content-box-main .text {
                font-size: 16px;
            }
            .trading-classic-content-box-footer .text {
                font-size: 16px;
            }
            .trading-classic-content-box-footer .price {
                font-size: 16px;
            }
            .trading-classic-content-box {
                min-height: 225px;
                max-height: 225px;
            }
            .trading-classic-content {
                height: 400PX;
            }
            .footer-section-content li i {
                font-size: 13px;
            }
            .footer-section-content li {
                font-size: 13px;
            }
            .main-section {
                padding-bottom: 50px;
            }.footer-section-content li {
                 padding: 0px 0px;
             }
            .header-title h6{
                font-size: 16px;
            }
        }
        @media (min-width: 768px) {
            .owl-nav-link {
                font-size: 16px;
                padding: 10px 15px;
            }
            .trading-classic-type {
                font-size: 16px;
            }
            .other-data-war {
                font-size: 16px;
            }
            .trading-classic-content-box-header-left .img img {
                width: 50px;
                height: 50px;
            }
            .texts {
                font-size: 16px;
            }
            .star {
                font-size: 16px;
                margin-right: 10px;
            }
            .btn-edit {
                padding: 5px 10px !important;
                font-size: 13px;
            }
            .trading-classic-content-box-main .text {
                font-size: 16px;
            }
            .trading-classic-content-box-footer .text {
                font-size: 16px;
            }
            .trading-classic-content-box-footer .price {
                font-size: 16px;
            }
            .trading-classic-content-box {
                min-height: 225px;
                max-height: 225px;
            }
            .trading-classic-content {
                height: 400PX;
            }
            .footer-section-content li i {
                font-size: 13px;
            }
            .footer-section-content li {
                font-size: 13px;
            }
            .main-section {
                padding-bottom: 50px;
            }.footer-section-content li {
                 padding: 0px 0px;
             }
            .header-title h6{
                font-size: 16px;
            }
        }

        #buyCopyTradeModal .modal-dialog{
            background: #00000087 !important;
        }
        #buyCopyTradeModal .modal-content{
            background: #222223 !important;
        }
        .btn-confirm{
            background-color: blue !important;
        }
        .trading-classic-content-box{
            padding: 15px !important;
        }
        .header-title h6 {
            font-size: 20px !important;
            font-weight: bold;
        }
        .nav-link, .trading-classic-type, .other-data-war {
            font-size: 18px !important;
            font-weight: 600;
        }
        .texts span:first-child {
            font-size: 16px !important;
            font-weight: bold;
        }
        .texts span {
            font-size: 14px !important;
        }
        .trading-classic-content-box-footer .text,
        .trading-classic-content-box-main .text {
            font-size: 13px !important;
        }
        .trading-classic-content-box-footer .price,
        .trading-classic-content-box-main .price {
            font-size: 22px !important;
            font-weight: bold;
        }
        .btn-edit {
            font-size: 16px !important;
            font-weight: bold;
        }
        .star {
            font-size: 18px !important;
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
                    <h6 class="white">Copy Trading Classic</h6>
                </div>
                <div class="other-data">
                    <div class="other-data-war d-none">My Trades</div>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="main-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="all-other-services">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link owl-nav-link active nv-copy-tab-btn" id="pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">Leaderboard</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link owl-nav-link nv-copy-tab-btn" id="pills-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-profile" type="button" role="tab"
                                    aria-controls="pills-profile" aria-selected="false">All Trading</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link owl-nav-link nv-copy-tab-btn" id="pills-contact-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-contact" type="button" role="tab"
                                    aria-controls="pills-contact" aria-selected="false">Subscribed</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                             aria-labelledby="pills-home-tab">
                            <div class="trading-classic">
                                <div class="d-flex justify-content-between">
                                    <div class="trading-classic-type">
                                        ROI <i class="fa-solid fa-circle-question question"></i>
                                    </div>
                                    <div class="other-data-war d-none">View All <i class="fa-solid fa-angle-right ms-1"></i></div>
                                </div>
                            </div>
                            <div class="trading-classic-content">
                                @foreach($stared_trades as $stared_trade)
                                <div class="trading-classic-content-box">
                                    <div class="trading-classic-content-box-header">
                                        <div class="trading-classic-content-box-header-left">
                                            <div class="img">
                                                <img src="{{ getImage(getFilePath('currency') .'/'.$stared_trade->image,getFileSize('currency')) }}" alt="">
                                            </div>
                                            <div class="texts ms-2">
                                                <span>{{$stared_trade->name}}</span> <br>
                                                <span>0000 <span class="ms-4 text-success">{{$stared_trade->interest}}% </span></span>
                                            </div>
                                        </div>
                                        <div class="trading-classic-content-box-header-right">
                                            <i class="fa-solid fa-star star"></i>
                                            <button class="btn btn-edit btn-sm trade-copy-btn buyCopyTrade"
                                                    data-name="{{strtoupper($stared_trade->name)}}" data-id="{{$stared_trade->id}}">Copy</button>
                                        </div>
                                    </div>
                                    <div class="trading-classic-content-box-main mt-4">

                                        <div class="text">
                                            {{mb_strimwidth($stared_trade->short_details,0,70,'...')}}
                                        </div>
                                        <div class="price "></div>
                                    </div>
                                    <div class="trading-classic-content-box-footer pt-3">
                                        <div class="left">
                                            <div class="text">ROI
                                                @if($stared_trade->type=='daily')
                                                    1D
                                                @elseif($stared_trade->type=='weekly')
                                                    7D
                                                @elseif($stared_trade->type=='monthly')
                                                    30D
                                                @elseif($stared_trade->type=='yearly')
                                                    365D
                                                @endif
                                            </div>
                                            <div class="price text-success">{{$stared_trade->interest}}%</div>
                                        </div>
                                        <div class="right">
                                            <div class="text">Drawdown {{$stared_trade->dw_day}}D</div>
                                            <div class="price">{{$stared_trade->dw_profit}}%</div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                             aria-labelledby="pills-profile-tab">


                            <div class="trading-classic">
                                <div class="d-flex justify-content-between">
                                    <div class="trading-classic-type">
                                        ROI <i class="fa-solid fa-circle-question question"></i>
                                    </div>
                                    <div class="other-data-war d-none">View All <i class="fa-solid fa-angle-right ms-1"></i></div>
                                </div>
                            </div>
                            <div class="trading-classic-content">
                                @foreach($trades as $trade)
                                    <div class="trading-classic-content-box">
                                        <div class="trading-classic-content-box-header">
                                            <div class="trading-classic-content-box-header-left">
                                                <div class="img">
                                                    <img src="{{ getImage(getFilePath('currency') .'/'.$trade->image,getFileSize('currency')) }}" alt="">
                                                </div>
                                                <div class="texts ms-2">
                                                    <span>{{$trade->name}}</span> <br>
                                                    <span>0000 <span class="ms-4 text-success">{{$trade->interest}}% </span></span>
                                                </div>
                                            </div>
                                            <div class="trading-classic-content-box-header-right">
                                                @if($trade->stared=='yes')
                                                <i class="fa-solid fa-star star"></i>
                                                @endif
                                                <button class="btn btn-edit btn-sm trade-copy-btn buyCopyTrade"
                                                        data-name="{{strtoupper($trade->name)}}" data-id="{{$trade->id}}">Copy</button>
                                            </div>
                                        </div>
                                        <div class="trading-classic-content-box-main mt-4">

                                            <div class="text">
                                                {{mb_strimwidth($trade->short_details,0,70,'...')}}
                                            </div>
                                            <div class="price "></div>
                                        </div>
                                        <div class="trading-classic-content-box-footer pt-3">
                                            <div class="left">
                                                <div class="text">ROI
                                                    @if($trade->type=='daily')
                                                        1D
                                                    @elseif($trade->type=='weekly')
                                                        7D
                                                    @elseif($trade->type=='monthly')
                                                        30D
                                                    @elseif($trade->type=='yearly')
                                                        365D
                                                    @endif
                                                </div>
                                                <div class="price text-success">{{$trade->interest}}%</div>
                                            </div>
                                            <div class="right">
                                                <div class="text">Drawdown {{$trade->dw_day}}D</div>
                                                <div class="price">{{$trade->dw_profit}}%</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                        </div>

                        <div class="tab-pane fade" id="pills-contact" role="tabpanel"
                             aria-labelledby="pills-contact-tab">
                            @foreach($classic_trades as $classic_trade)
                                <div class="trading-classic-content-box">
                                    <div class="trading-classic-content-box-header">
                                        <div class="trading-classic-content-box-header-left">
                                            <div class="img">
                                                <img src="{{ getImage(getFilePath('currency') .'/'.$classic_trade->trade->image,getFileSize('currency')) }}" alt="">
                                            </div>
                                            <div class="texts ms-2">
                                                <span>{{$classic_trade->trade->name}}</span> <br>
                                                <span>000 <span class="ms-4 text-success">{{$classic_trade->trade->interest}}% </span></span>
                                            </div>
                                        </div>
                                        <div class="trading-classic-content-box-header-right">
                                            <i class="fa-solid fa-star star"></i>
                                            @php
                                                $start_date=\Illuminate\Support\Carbon::parse($classic_trade->trade->start_date);
                                            @endphp
                                            @if($classic_trade->status=='buy' &&  $start_date < now())
                                                <button class="btn btn-edit btn-sm trade-copy-btn withdrawCopyTrade"
                                                        data-name="{{strtoupper($classic_trade->trade->name)}}"
                                                        data-id="{{$classic_trade->id}}">Withdraw</button>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="trading-classic-content-box-main mt-4">

                                        <div class="text">
                                            {{mb_strimwidth($classic_trade->trade->short_details,0,70,'...')}}
                                        </div>
                                        <div class="price "></div>
                                    </div>
                                    <div class="trading-classic-content-box-footer pt-3">
                                        <div class="left">
                                            <div class="text">ROI
                                                @if($classic_trade->trade->type=='daily')
                                                    1D
                                                @elseif($classic_trade->trade->type=='weekly')
                                                    7D
                                                @elseif($classic_trade->trade->type=='monthly')
                                                    30D
                                                @elseif($classic_trade->trade->type=='yearly')
                                                    365D
                                                @endif

                                            </div>
                                            <div class="price text-success">{{$classic_trade->trade->interest}}%</div>
                                        </div>
                                        <div class="right">
                                            <div class="text">Drawdown {{$classic_trade->trade->dw_day}}D</div>
                                            <div class="price">{{$classic_trade->trade->dw_profit}}%</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer-section">
    <div class="footer-section-content">
        <div class="container">
            <ul>
                <li class="active">
                    <a href="{{route('user.classic.trading')}}">
                        <i class="fa-solid fa-rotate"></i>
                        <div>Classic</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('user.gold.fx.trading')}}">
                        <i class="fa-solid fa-coins"></i>
                        <div>Gold&FX</div>
                    </a>
                </li>
                <li>
                    <a href="">
                        <i class="fa-brands fa-product-hunt"></i>
                        <div>Pro</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>


@include('templates.basic.user.copy_trading.includes.modal')
@endsection

@push('script')
    @include('templates.basic.user.copy_trading.includes.modal_js')
@endpush
