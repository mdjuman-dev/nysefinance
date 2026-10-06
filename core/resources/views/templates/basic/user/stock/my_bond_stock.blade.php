@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3">
        <div class="col-12">

            <div class="row">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active " id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#tab-sell-stock" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">Buy Bond
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#tab-buy-stock" type="button" role="tab" aria-controls="pills-profile"
                                aria-selected="false">Sell Bond
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link d-none" id="pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#tab-exchange-stock" type="button" role="tab" aria-controls="pills-profile"
                                aria-selected="false">Exchange
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="tab-sell-stock" role="tabpanel"
                         aria-labelledby="pills-home-tab" tabindex="0">

                        <div class="table-responsive--md  table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($buy_products as $product)

                                    @php
                                        if($product->type=='fix'){
                                            $totalInterest=\App\Models\StockTransaction::where('type', 'interest')->where('stock_id', $product->id)->sum('amount');
                                    }else{
                                      $totalInterest=null;
                                    }
                                    @endphp

                                    <tr>
                                        <td>
                                            <img class="my-stock-image buy-stock-im" src="{{ getImage(getFilePath('currency') .'/'.$product->product->image,getFileSize('currency')) }}">
                                        </td>


                                        <td class="cs-br">
                                            {{$product->product->name}}
                                            @if($product->type=='fix')
                                            @if($totalInterest)
                                                <div>
                                                    <small><b>Interests: </b>   <span style="color: #09dc09;">{{$totalInterest}} USD</span></small>
                                                </div>
                                            @endif
                                            @else
                                            <div>
                                                <span class="market-price-sec">Market Price:  <strong class="each-live-price" data-stack="{{$product->stack_price}}" data-code="{{$product->product->stock_code}}">---/---</strong></span>
                                            </div>
                                            @endif
                                        </td>


                                        <td class="cs-br">${{$product->invest_amount}}</td>

                                        @php
                                            // Ensure the purchase date is not in the future and calculate the difference
                                            $daysSincePurchase = $product->created_at->diffInDays(now());
                                        @endphp

                                        <td class="cs-br">

                                            <div class="btn-group">
                                                @if ($product->type=='fix' && $product->invest_date && $product->invest_date <= now())
                                                <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-bs-expanded="false">
                                                    <span>Action</span>
                                                </button>
                                                @else
                                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-bs-expanded="false">
                                                        <span>Action</span>
                                                    </button>
                                                @endif
                                                <div class="dropdown-menu" role="menu" style="    background: rgb(196 196 196) !important;padding:10px;">
                                                    <button type="button" data-url="{{route('public.certificate',[$product->certificate_id])}}"
                                                            class="generateQrCode dropdown-item btn--info text-white" data-id="{{$product->id}}">
                                                        <i class="fa fa-eye"></i> Certificate
                                                    </button>
                                                    @if ($product->type=='fix' && $product->invest_date && $product->invest_date <= now())
                                                        <button type="button"
                                                                data-url="{{route('user.stock.sell',[$product->id])}}"
                                                                data-name="{{$product->product->name}}" class="sell_stock text-white dropdown-item btn--danger mt-2">Sell</button>
                                                        <a href="{{route('user.stock.exchange',[$product->id])}}" class="dropdown-item text-white btn--info mt-2">Exchange</a>
                                                        <a data-url="{{route('user.stock.reactive',[$product->id])}}" href="#" class="dropdown-item text-white btn--warning mt-2 reactiveStock">Reactive</a>
                                                    @elseif($product->type=='unfix')

                                                        <button type="button"
                                                                data-url="{{route('user.stock.sell',[$product->id])}}"
                                                                data-name="{{$product->product->name}}" class="sell_stock text-white dropdown-item btn--danger mt-2">Sell</button>
                                                        <a href="{{route('user.stock.exchange',[$product->id])}}" class="dropdown-item text-white btn--info mt-2">Exchange</a>
                                                        <a data-url="{{route('user.stock.reactive',[$product->id])}}" href="#" class="dropdown-item text-white d-none btn--warning mt-2 reactiveStock">Reactive</a>

                                                    @endif
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">No Data Available</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>


                    </div>
                    <div class="tab-pane fade" id="tab-buy-stock" role="tabpanel" aria-labelledby="pills-profile-tab"
                         tabindex="0">

                        <div class="table-responsive--md  table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($sell_products as $s_product)

                                    <tr>
                                        <td>
                                            <img class="my-stock-image" src="{{ getImage(getFilePath('currency') .'/'.$s_product->product->image,getFileSize('currency')) }}">
                                        </td>
                                        <td class="cs-br">{{$s_product->product->name}}</td>
                                        <td class="cs-br">${{$s_product->invest_amount}}</td>

                                        <td class="cs-br">
                                            <button type="button" data-url="{{route('public.certificate',[$s_product->certificate_id])}}" class="btn btn-info generateQrCode btn-sm" data-id="{{$s_product->id}}">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">No Data Available</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>


                    <div class="tab-pane fade" id="tab-exchange-stock" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">

                        <div class="table-responsive--md  table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Broker')</th>
                                    <th>@lang('Amount')</th>
                                    <th>Charge</th>
                                    <th>Status</th>
                                    <th>Transfer Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($exchange_products as $e_product)

                                    <tr>
                                        <td>
                                            <img class="my-stock-image" src="{{ getImage(getFilePath('currency') .'/'.$e_product->product->image,getFileSize('currency')) }}">
                                        </td>
                                        <td class="cs-br">{{$e_product->product->name}}</td>
                                        <td class="cs-br">{{$e_product->broker}}</td>
                                        <td class="cs-br">${{$e_product->user_stock->invest_amount}}</td>
                                        <td class="cs-br">${{$e_product->charge}}</td>

                                        <td class="cs-br">
                                            @if($e_product->status=='approved')
                                                <span class="badge badge--success">Approved</span>
                                            @else
                                                <span class="badge badge--danger">{{ucwords($e_product->status)}}</span>
                                            @endif
                                        </td>

                                        <td class="cs-br">
                                            @if($e_product->status=='approved')
                                            {{$e_product->updated_at->format('Y-m-d h:i a')}}
                                            @else
                                                --/--/--
                                            @endif
                                        </td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">No Data Available</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="sellModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="sellModalForm" method="post">
                    @csrf

                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLongTitle">Confirm</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5>Are you sure you want to sell <span style="font-weight: bold" class="modal-stock-name"></span> stock?</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="reactiveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="reactiveModalForm" method="get">

                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLongTitle">Confirm</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <label class="d-block" for="">Holding time</label>
                            <select name="invest_time" class="form-control">
                                <option value="month">1 Month</option>
                                <option value="three_month">3 Month</option>
                                <option value="half_year">6 Month</option>
                                <option value="year">1 Year</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>

                </form>
            </div>
        </div>
    </div>




    <x-confirmation-modal isCustom="true"/>


    <!-- Modal -->
    <div class="modal fade" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Stock QR Code</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mx-auto pt-3 pb-3">
                            <div class="qr-section pt-">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('topContent')
    <h4 class="mb-4">
        {{ __($pageTitle) }}

        <span style=" float: right; font-size: 13px;margin-top: 5px;">Total Bond Buy: ${{$total_buy_stocks}}</span>
    </h4>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">

    <style>
        .my-stocks {
            float: right;
            padding: 10px 20px;
        }
        .my-stock-image{
            border-radius: 50px;
            height: 45px;
            width: 50px;
        }

        .main-stock-section {
            display: flex;
            background: #fffbfb;
            border-radius: 32px;
            padding: 5px;
            cursor: pointer;
        }

        .stock-image img {
            height: 100%;
            width: 100%;
            border-radius: 50px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        }

        .stock-image {
            height: 55px;
            width: 55px;
        }

        .stock-name {
            font-size: 18px;
            margin: auto;
            text-align: left;
            width: 89%;
            padding-left: 15px;
            font-weight: 500;
            color: #524949;
        }

        .stock-description {
            display: block;
            font-size: 12px;
        }
        .qr-section img{
            width: 100%;
            height: 100%;
        }
        .qr-section{
            width: 200px;
            text-align: center;
            margin: 0 auto;
        }
        .table{
            min-width: 480px !important;
        }

        @media(max-width: 700px) {
            .buy-stock-im{
                height: 30px !important;
                width: 30px !important;
            }
            .table thead tr th{
                font-size: 11px !important;
            }
            .table{
                min-width: 580px !important;
                overflow: scroll;
            }
            .table--light{
                min-height: 285px;
            }
        }
    </style>
@endpush


@push('script')
    <script>
        "use strict";

        $(document).on('click', '.reactiveStock', function(e){
            const dataUrl=$(this).attr('data-url');


            $('#reactiveModalForm').attr('action', dataUrl);

            $('#reactiveModal').modal('show');

        })

        {{--$(document).ready(function(){--}}


        {{--    $('.each-live-price').each(function() {--}}

        {{--        const code = $(this).attr('data-code');--}}
        {{--        const stack_price = parseFloat($(this).attr('data-stack'));--}}

        {{--        if(stack_price && stack_price > 0){--}}

        {{--            $.ajax({--}}
        {{--                type:'GET',--}}
        {{--                url:'{{route('user.stock.live.price')}}',--}}
        {{--                data:{--}}
        {{--                    code:code--}}
        {{--                },--}}

        {{--                success:function(res){--}}
        {{--                    if(res.status=='success'){--}}
        {{--                        if(res.amount && res.amount > 0){--}}
        {{--                            if(stack_price < res.amount){--}}
        {{--                                $(this).text(`<span class="text-success">${res.amount}</span>`)--}}
        {{--                            }else{--}}
        {{--                                $(this).text(`<span class="text-danger">${res.amount}</span>`)--}}
        {{--                            }--}}
        {{--                        }else{--}}
        {{--                            $(this).text(`<span class="text-danger">---/---</span>`)--}}
        {{--                        }--}}
        {{--                    }--}}
        {{--                }--}}
        {{--            })--}}
        {{--        }--}}
        {{--    });--}}
        {{--    --}}
        {{--});--}}



        $(document).ready(function(){
            function fetchLivePrices() {
                $('.each-live-price').each(function() {
                    const code = $(this).attr('data-code');
                    const stack_price = parseFloat($(this).attr('data-stack'));

                    if(stack_price && stack_price > 0) {
                        $.ajax({
                            type: 'GET',
                            url: '{{route('user.stock.live.price')}}',
                            data: {
                                code: code
                            },
                            success: function(res) {
                                if(res.status == 'success') {
                                    if(res.amount && res.amount > 0) {
                                        if(stack_price < res.amount) {
                                            $(this).html(`<span class="text-success">${res.amount}</span>`);
                                        } else {
                                            $(this).html(`<span class="text-danger">${res.amount}</span>`);
                                        }
                                    } else {
                                        $(this).html('<span class="text-danger">---/---</span>');
                                    }
                                }
                            }.bind(this) // bind 'this' to the current element in the success callback
                        });
                    }
                });
            }

            // Initial fetch
            fetchLivePrices();

            // Set interval to fetch prices every 20 minutes (20 * 60 * 1000 milliseconds)
            setInterval(fetchLivePrices, 20 * 60 * 1000);
        });





        $(document).on('click', '.main-stock-section', function (e) {
            const url = $(this).attr('data-url');

            location.href = url;
        })


        $(document).on('click', '.generateQrCode', function (e) {

            const id=$(this).attr('data-id');
            const data_url=$(this).attr('data-url');

            $('.qr-section').html('');

            $.ajax({
                type:'POST',
                url:'{{route('user.stock.qr.code')}}',
                data:{
                    id:id,'_token':'{{csrf_token()}}'
                },

                success:function (res){
                    if(res.status=='success'){
                        $('.qr-section').html(` <img src="data:image/svg+xml;base64,${res.data}"><a class="mt-4 btn-sm btn btn--info" href="${data_url}"  >Certificate Preview</a>`);

                        $('#qrCodeModal').modal('show');
                    }
                }
            })



        })

        $(document).on('click', '.sell_stock', function (e) {
            const url = $(this).attr('data-url');
            const name = $(this).attr('data-name');

            $('.modal-stock-name').text(name)
            $('#sellModalForm').attr('action', url);
            $('#sellModal').modal('show');

        })

    </script>
@endpush
