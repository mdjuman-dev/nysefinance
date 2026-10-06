@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3">
        <div class="col-12">

            <form action="{{route('user.stock.exchange.request')}}" method="post">
                @csrf

                <input type="hidden" name="stock_id" value="{{$stock->id}}">

                <div class="row justify-content-center" style="justify-content: center">
                    <div class="col-md-6">
                        <div class="form-group buyer-section">
                            <h4 class="mb-3">
                                {{$stock->product->name}}
                            </h4>

                            <div class="mt-2">
                                <div>
                                    <span class="exchange-title">Stock Holder Name &nbsp;: </span> <span
                                        class="exchange-value">{{auth()->user()->fullname}}</span>
                                </div>
                                <div>
                                    <span class="exchange-title">Stock Size Or Amount &nbsp;: </span> <span
                                        class="exchange-value">{{$stock->invest_amount}}</span>
                                </div>
                                <div>
                                    <span class="exchange-title">Buy Date &nbsp;: </span> <span
                                        class="exchange-value">{{$stock->created_at->format('Y-m-d h:i a')}}</span>
                                </div>
                                <div>
                                    <span class="exchange-title">Stock Status &nbsp;: </span>
                                    <span class="exchange-value">
                                       @if($stock->type=='fix')
                                            <span class="badge badge--danger">Mutual Fund</span>
                                        @elseif($stock->type=='unfix')
                                            <span class="badge badge--success">Live Market</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="d-flex">
                                    <span class="exchange-title">Comment &nbsp;: </span>
                                    <span class="exchange-value">
                                        <input type="text" name="comment" style="height: 35px !important;" class="form-control form--control" placeholder="Enter your comment">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group seller-section">
                            <h4 class="mb-3">
                                Broker
                            </h4>

                            <div class="mt-2">
                                <div>
                                    <span class="exchange-title">Stock Holder Name &nbsp;: </span> <span
                                        class="exchange-value">{{auth()->user()->fullname}}</span>
                                </div>
                                <div class="d-flex">
                                    <span class="exchange-title">Stock Size Or Amount &nbsp;: </span> <span
                                        class="exchange-value">{{$stock->invest_amount}}</span>
                                </div>
                                <div class="d-flex">
                                    <span class="exchange-title">Broker & Exchange &nbsp;: </span>
                                    <span  class="exchange-value">
                                        <select name="broker" class="form-control form--control" style="height: 35px !important;padding: 2px 10px !important;">
                                            <option value="Default">--Choose Broker--</option>
                                            @foreach($brokers as $broker)
                                                <option value="{{$broker->name}}">{{$broker->name}}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                </div>
                                <div class="d-flex">
                                    <span class="exchange-title">Receive Account &nbsp;: </span>
                                    <span class="exchange-value">
                                       <input type="text" name="receive_amount" style="height: 35px !important;" class="form-control form--control" placeholder="Enter Receiver Email">
                                    </span>
                                </div>
                                <div class="d-flex">
                                    <span class="exchange-title">Contact Email &nbsp;: </span>
                                    <span class="exchange-value">
                                       <input type="text" name="contact_email" style="height: 35px !important;" class="form-control form--control" placeholder="Enter Contact Email">
                                    </span>
                                </div>
                                <div class="d-flex">
                                    <span class="exchange-title">Charge &nbsp;: </span>
                                    <span class="exchange-value">
                                        {{$charge_amount}} USD
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4 text-right float-right">
                            <button class="btn btn--success" style="float: right" type="submit">Submit</button>
                        </div>
                    </div>


                </div>


            </form>

        </div>
    </div>


    <x-confirmation-modal isCustom="true"/>
@endsection

@push('topContent')
    <h4 class="mb-4">{{ __($pageTitle) }}</h4>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">

    <style>

        span.exchange-title{
            margin-bottom: 19px;
            margin-right: 20px;
        }
        .buyer-section,.seller-section{
            padding: 20px;
            background: #071c21;
            border-radius: 5px;
        }
        input,select{
            border-color: #6e6d6d !important;
        }

    </style>
@endpush


@push('script')
    <script>
        "use strict";

        $(document).on('click', '.custom-stock-btn', function (e) {
            e.preventDefault();

            const name = $(this).attr('data-name');
            $('.unfix_amount').val(name);

            $('.custom-stock-btn').removeClass('active');
            $(this).addClass('active');
        });


        $(document).on('click', '.buyStock', function (e) {

            $('#butStockModal').modal('show');
        });

        $(document).on('click', '.choose-stock-type', function (e) {

            const type = $(this).val();
            if (type == 'unfix') {
                $('.custom-stock-section').show();
                $('.stock-fix-section').hide();
            } else {
                $('.custom-stock-section').hide();
                $('.stock-fix-section').show();
            }
        });

    </script>
@endpush
