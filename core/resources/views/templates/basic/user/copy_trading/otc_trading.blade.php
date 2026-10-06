@extends($activeTemplate . 'layouts.master')

@push('topContent')

@endpush
@push('ip-css')
    <link rel="stylesheet" href="{{asset('core/public/style.css')}}">

    <style>
        .bybit-title {
            text-align: center;
        }
        .bybit-title .title{
            font-size: 11px;
            font-weight: 500;
            color: #fff;
        }
        .bybit-title .sub-title{
            margin: 5px 0px;
            font-size: 16px;
            font-weight: 600;
            background: linear-gradient(to right, #FFB200 0%, #EB5B00 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bybit-title .footer-title{
            font-size: 10px;
            font-weight: 500;
            color: #a19f9fbd;
        }
        .deomTrading{
            margin-top: 20px;
            text-align: center;
            background: linear-gradient(to right, #FFB200 0%, #EB5B00 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .deomTrading i{
            font-weight: 600;
            font-size: 15px;
        }
        .deomTrading span{
            font-weight: 700;
        }
        .deomTrading .img img{
            width: 100%;
            height: 230px;
        }
        .productAdvantages{
            text-align: center;
            margin: 20px 0;
        }
        .productAdvantages .title{
            font-size: 11px;
            font-weight: 600;
            color: #fff;
        }
        .productAdvantages .product {
            margin: 10px 0px;
            padding: 5px 10px;
            border-radius: 7px;
            background-color: #131010;
        }
        .productAdvantages .product .text {
            text-align: left;
            padding: 0 10px;
        }
        .productAdvantages .product .icon {
            text-align: left;
            color: #fff;
            font-size: 20px;
            padding: 0 10px;
        }
        .productAdvantages .product .text .title{
            font-size: 13px;
        }
        .productAdvantages .product .text .des{
            font-size: 10px;
            color: #666464;
        }
        @media (min-width: 992px) {
            .header-title h6{
                font-size: 16px;
            }
            .bybit-title .title {
                font-size: 16px;
            }
            .bybit-title .sub-title {
                font-size: 40px;
            }
            .bybit-title .footer-title {
                font-size: 16px;
            }
            .deomTrading .img img {
                height: 100%;
            }
            .productAdvantages .title {
                font-size: 16px;
            }
            .productAdvantages .product .text .title {
                font-size: 16px;
            }
            .productAdvantages .product .text .des {
                font-size: 15px;
            }
            .productAdvantages .product .icon {
                font-size: 30px;
                padding: 0 15px;
            }
            .btn-footer {
                height: 40px;
                font-size: 16px;
            }
        }
        @media (min-width: 768px) {
            .header-title h6{
                font-size: 16px;
            }
            .bybit-title .title {
                font-size: 16px;
            }
            .bybit-title .sub-title {
                font-size: 40px;
            }
            .bybit-title .footer-title {
                font-size: 16px;
            }
            .deomTrading .img img {
                height: 100%;
            }
            .productAdvantages .title {
                font-size: 16px;
            }
            .productAdvantages .product .text .title {
                font-size: 16px;
            }
            .productAdvantages .product .text .des {
                font-size: 15px;
            }
            .productAdvantages .product .icon {
                font-size: 30px;
                padding: 0 15px;
            }
            .btn-footer {
                height: 40px;
                font-size: 16px;
            }
        }

        .bybit-title{
            padding: 20px 10px !important;
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
                        <h6 class="white">NyseFinance MT5</h6>
                    </div>
                    <div class="other-data">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="main-section mt-2 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="bybit-title">
                        <div class="title">
                            Unleash your trading potential in financial markets
                        </div>
                        <div class="sub-title">
                            NyseFinance OTC Trading
                        </div>
                        <div class="footer-title">
                            Cryptocurrency | forex | CFD (Metal, Oil and Indices)
                        </div>
                    </div>
                    <div class="deomTrading">
                        <h6> <i class="fa-regular fa-share-from-square me-1"></i> <span>Demo Trading</span> <i class="fa-solid fa-arrow-right ms-1"></i></h6>
                        <div class="img mt-2">
                            <img src="{{asset('core/public/img/images__1_-removebg-preview.png')}}" alt="">
                        </div>
                    </div>
                    <div class="productAdvantages">
                        <div class="title">Product Advantages</div>
                        <div class="products">
                            <div class="row">
                                <div class="col-12">
                                    <div class="product d-flex align-items-center">
                                        <div class="icon">
                                            <i class="fa-solid fa-thumbs-up"></i>
                                        </div>
                                        <div class="text ">
                                            <div class="title">Competitive Prices</div>
                                            <div class="des">Get single quote before your trade to ensure your entire lot is traded at the agreed price.</div>
                                        </div>
                                    </div>
                                    <div class="product d-flex align-items-center">
                                        <div class="icon">
                                            <i class="fa-solid fa-thumbs-up"></i>
                                        </div>
                                        <div class="text ">
                                            <div class="title">Competitive Prices</div>
                                            <div class="des">Get single quote before your trade to ensure your entire lot is traded at the agreed price.</div>
                                        </div>
                                    </div>
                                    <div class="product d-flex align-items-center">
                                        <div class="icon">
                                            <i class="fa-solid fa-thumbs-up"></i>
                                        </div>
                                        <div class="text ">
                                            <div class="title">Competitive Prices</div>
                                            <div class="des">Get single quote before your trade to ensure your entire lot is traded at the agreed price.</div>
                                        </div>
                                    </div>
                                </div>
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
                <button class="btn btn-footer coming_soon">Start OTC Trading</button>
            </div>
        </div>
    </footer>


@endsection


@push('script')

    <script>
        $(document).on('click', '.buyCopyTrade', function (e){
            const id=$(this).attr('data-id');
            const name=$(this).attr('data-name');
            $('.copy_trade_id').val(id);
            $('.trade_name').text(name);
            $('#buyCopyTradeModal').modal('show');

        });


        $(document).on('click', '.withdrawCopyTrade', function (e){
            const id=$(this).attr('data-id');
            const name=$(this).attr('data-name');
            $('.w_copy_trade_id').val(id);
            $('.w_trade_name').text(name);
            $('#withdrawCopyTradeModal').modal('show');

        });
    </script>

@endpush
