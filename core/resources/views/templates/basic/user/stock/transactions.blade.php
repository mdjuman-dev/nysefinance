@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3">
        <div class="col-12">

            <div class="row">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="{{route('user.stock.transactions')}}" class="nav-link {{isset($transactions)?'active':''}}" id="pills-home-tab">Transactions
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{route('user.stock.transaction.trx')}}" class="nav-link {{isset($interests)?'active':''}}">Interests
                        </a>
                    </li>
                </ul>
                <form action="" class="mt-2 mb-4" method="get">

                    <div class="row mx-auto">
                        <div class="col-md-5 col-8">
                            <input type="text" name="filter_date" placeholder="Choose Date" autocomplete="off" class="form-control">
                        </div>
                        <div class="col-md-2 col-4">
                            <button class="btn btn--success" type="submit">Search..</button>
                        </div>
                    </div>

                </form>

                <div class="tab-content" id="pills-tabContent">




                    <div class="tab-pane fade {{isset($transactions)?'show active':''}}" id="tab-sell-stock" role="tabpanel"
                         aria-labelledby="pills-home-tab" tabindex="0">

                        <div class="table-responsive--md  table-responsive">
                            @if(isset($transactions) && $transactions)
                            <div class="col-lg-12">
                                <div class="table-wrapper">
                                    <table class="table table--responsive--lg">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('For')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Type')</th>
                                            <th>@lang('Remark')</th>
                                            <th>@lang('Date')</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($transactions as $keys=>$transaction)
                                            <tr>
                                                <td>{{++$keys}}</td>
                                                <td>
                                                    @if($transaction->stock_id && $transaction->user_stock)
                                                    {{$transaction->user_stock->product->name}}
                                                    @else
                                                    N\A
                                                    @endif
                                                </td>
                                                <td>{{$transaction->amount}}</td>
                                                <td>
                                                    @if($transaction->type=='sell')
                                                    <span class="badge badge--danger"> {{strtoupper($transaction->type)}}</span>
                                                    @elseif($transaction->type=='buy')
                                                    <span class="badge badge--success"> {{strtoupper($transaction->type)}}</span>
                                                    @else
                                                    <span class="badge badge--success"> {{strtoupper($transaction->type)}}</span>
                                                    @endif


                                                </td>
                                                <td>{{$transaction->remark}}</td>
                                                <td>{{$transaction->created_at->format('d-m-Y h:i A')}}</td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">No Data Available</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if ($transactions->hasPages())
                                    {{ paginateLinks($transactions) }}
                                @endif
                            </div>
                            @else
                            <div class="col-md-12 text-center">
                                No Data
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane fade {{isset($interests)?'show active':''}}" id="tab-buy-stock" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">

                        <div class="table-responsive--md  table-responsive">
                            @if(isset($interests) && $interests)
                            <div class="col-lg-12">
                                <div class="table-wrapper">
                                    <table class="table table--responsive--lg">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('For')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Remark')</th>
                                            <th>@lang('Date')</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($interests as $key=>$interest)
                                            <tr>
                                                <td>{{++$key}}</td>
                                                <td>
                                                    @if($interest->stock_id && $interest->user_stock)
                                                        {{$interest->user_stock->product->name}}
                                                    @else
                                                        N\A
                                                    @endif
                                                </td>
                                                <td>{{$interest->amount}}</td>
                                                <td>{{$interest->remark}}</td>
                                                <td>{{$interest->created_at->format('d-m-Y h:i A')}}</td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">No Data Available</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if ($interests->hasPages())
                                    {{ paginateLinks($interests) }}
                                @endif
                            </div>
                            @else
                                <div class="col-md-12 text-center">
                                    No Data
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>



    <x-confirmation-modal isCustom="true"/>
@endsection

@push('topContent')
    <h4 class="mb-4">
        {{ __($pageTitle) }}
    </h4>
@endpush

@push('script-lib')
{{--        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>--}}


    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js" ></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js" ></script>

    <script>
        $(document).ready(function (e){

            $('#filter_date').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#filter_date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });

            $('#filter_date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        })
    </script>

@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css" />

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
        .badge{
            font-size: 10px !important;
            padding: 7px 12px !important;
        }
    </style>
@endpush


@push('script')
    <script>
        "use strict";

        $(document).on('click', '.main-stock-section', function (e) {
            const url = $(this).attr('data-url');

            location.href = url;
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
