@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3">
        <div class="col-12">

            <div class="row">
                <form action="" class="mt-2 mb-4" method="get">

                    <div class="row mx-auto">
                        <div class="col-md-5 col-7">
                            <input type="text" name="filter_date" placeholder="Choose Date" autocomplete="off" class="form-control">
                        </div>
                        <div class="col-md-2 col-5">
                            <button class="btn btn--success btn-sm" type="submit">Search..</button>
                        </div>
                    </div>

                </form>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive--sm table-responsive">
                            <table class="table table--light style--two">
                                <thead>
                                <tr>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Charge')</th>
                                    <th>@lang('Transacted')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($transactions as $trx)
                                    <tr>

                                        <td class="budget">
                                            <span class="fw-bold text--success">
                                            {{ showAmount($trx->amount,currencyFormat:false) }}
                                            </span>
                                        </td>

                                        <td class="budget">
                                            <span class="fw-bold text--success">
                                            {{ showAmount($trx->charge,currencyFormat:false) }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at) }}
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                                </tbody>
                            </table>
                            <!-- table end -->
                        </div>
                    </div>
                    @if ($transactions->hasPages())
                        <div class="card-footer py-4">
                            {{ paginateLinks($transactions) }}
                        </div>
                    @endif
                </div><!-- card end -->

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
