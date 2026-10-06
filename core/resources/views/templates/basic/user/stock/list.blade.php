@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row stock-main-section">
        <div class="col-12">

                <div class="row">
                    @foreach($stocks as $stock)
                        <div class="col-md-6 col-12 mt-3">
                            <div class="main-stock-section" data-url="{{route('user.stock.details',[$stock->slug])}}">
                                <div class="stock-image">
                                    <img  src="{{ getImage(getFilePath('currency') .'/'.$stock->image,getFileSize('currency')) }}">
                                </div>
                                <div class="stock-name">
                                    {{$stock->name}}
{{--                                    <small class="stock-description">--}}
{{--                                        {!! mb_strimwidth($stock->description,0,80, '....') !!}--}}
{{--                                    </small>--}}
                                    <span class="each-price" data-code="{{$stock->stock_code}}">
                                        <span class="{{$stock->stock_code}}">0.00</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

        </div>
    </div>


    @if(!$stock_member)
    <div class="modal fade" id="stockMember" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('user.stock.member')}}" method="post">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Confirmation</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"  aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 class="text--dark">Are you sure you want to buy <b>Global Stock Membership</b>. It's cost 20 USDT?</h5>
                        <small class="text--danger">If you want to buy stock, at first you need to purchase <b>Global Stock Membership</b></small>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--success">Confirm</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    @endif


    <x-confirmation-modal isCustom="true"/>
@endsection

@push('topContent')
    <h4 class="mb-4">
        {{ __($pageTitle) }}

        <a href="{{route('user.stock.my')}}" class="btn btn-primary my-stocks">My Stocks</a>
    </h4>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">

    <style>
        .each-price{
            float: right;
            background: #ff002d94;
            padding: 2px 8px;
            border-radius: 5px;
        }
        .my-stocks{
            float: right;
            padding: 10px 20px;
        }
        .main-stock-section{
            display: flex;
            background: #6c6c6c54;
            border-radius: 32px;
            padding: 5px;
            cursor: pointer;
        }
        .stock-image img{
            height: 100%;
            width: 100%;
            border-radius: 50px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        }
        .stock-image{
            height: 55px;
            width: 55px;
        }
        .stock-name{
            font-size: 18px;
            margin: auto;
            text-align: left;
            width: 89%;
            padding-left: 15px;
            font-weight: 500;
            color: white;
        }
        .stock-description{
            display: block;
            font-size: 12px;
        }

        @media(max-width: 700px){
            .stock-main-section .main-stock-section .stock-name{
                color: #ffffff !important;
            }
            .stock-main-section .main-stock-section{
                background: #0f1016 !important;
                border-radius: 7px !important;
            }
            .stock-main-section{
                background: #08090ab0;
                padding-bottom: 20px;
                border-radius: 5px;
            }
            .stock-image {
                height: 40px !important;
                width: 40px !important;
            }
            .stock-name {
                font-size: 14px !important;
            }
        }
    </style>
@endpush


@push('script')

    <script>
        const apiKey='d49k3rhr01qlaebho1ngd49k3rhr01qlaebho1o0';

        const delayBetweenFetches = 1000; // 2 seconds between each stock
        const refreshAfter = 30000; // full refresh every 12 seconds

        // Sequential fetcher
        function fetchSequentially(index = 0) {
            const stocks = $('.each-price');
            if (index >= stocks.length) return; // all done

            const el = $(stocks[index]);
            const code = el.attr('data-code');
            const url = `https://finnhub.io/api/v1/quote?symbol=${code}&token=${apiKey}`;

            $.getJSON(url, function (data) {
                const price = data.c ? data.c.toFixed(2) : "N/A";
                $('.' + code).text('USD '+price);
            }).always(function () {
                // wait 2 seconds before next
                setTimeout(() => fetchSequentially(index + 1), delayBetweenFetches);
            });
        }

        // Main loop function
        function startLoop() {
            fetchSequentially();
            setTimeout(startLoop, refreshAfter);
        }

        // Start on page load
        $(document).ready(function() {
            startLoop();
        });
    </script>

    @if(!$stock_member)
        <script>
            "use strict";
            $(document).on('click', '.main-stock-section', function (e){
                $('#stockMember').modal('show');
            })

        </script>
    @else

    <script>
        "use strict";
        $(document).on('click', '.main-stock-section', function (e){
            const url=$(this).attr('data-url');

            location.href=url;
        })

    </script>
    @endif
@endpush
