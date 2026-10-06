@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3">
        <div class="col-12">

            <div class="row">

                <div class="col-md-12 mb-4">
                    <div class="row">
                        <div class="col-md-8 mx-auto text-center">
                            <h6>Total Interest: {{$total_interests}}</h6>
                        </div>
                    </div>
                </div>

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
                                        <tr class="text-center">
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
