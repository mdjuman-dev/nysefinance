@extends('admin.layouts.app')
@section('panel')

    <div class="row mb-none-30 mb-3 align-items-center gy-4 justify-content-end mb-4">

        <div class="col-md-3 mx-end">
            <form action="" id="searchF">
                <select name="user_id" class="form-control search-u">
                    <option value="">--Select User--</option>
                    <option value="all">All Users</option>
                    @foreach($users as $user)
                        <option {{ request()->get('user_id') && request()->get('user_id')==$user->id?'selected':'' }} value="{{$user->id}}">{{$user->fullname}}</option>
                    @endforeach
                </select>
            </form>
        </div>

    </div>

    <div class="row mb-none-30 mb-3 align-items-center gy-4">
        <div class="col-md-12 mt-3">
            <h5>USER WALLET INFORMATION</h5>
        </div>
        <div class="col-xxl-4 col-sm-4 mt-1">
            <x-widget style="6" link="#" icon="las la-sync f-size--56" title="Total Spot USDT Balance"
                      value="{{number_format($total_spot_usdt, 3)}}" bg="primary" />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-4 mt-1">
            <x-widget style="6" link="#" icon="las la-coins f-size--56" title="Total Funding USDT Balance"
                      value="{{number_format($total_fund_usdt, 3)}}" bg="success" />
        </div>
        <div class="col-xxl-4 col-sm-4 mt-1">
            <x-widget style="6" link="#" icon="las la-sync f-size--56" title="Total Stock Balance"
                      value="{{number_format($stock_wallet, 3)}}" bg="danger" />
        </div>
    </div>




    <div class="row mb-none-30 mb-3 align-items-center gy-4">
        <div class="col-md-12 mt-5">
            <h5>STOCK INFORMATION</h5>
        </div>
        <div class="col-xxl-3 col-sm-3 mt-1">
            <x-widget style="6" link="#" icon="las la-sync f-size--56" title="Total Stock Buy"
                      value="{{number_format($total_stock_buy, 3)}}" bg="primary" />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-3 mt-1">
            <x-widget style="6" link="#" icon="las la-coins f-size--56" title="Total Stock Sell"
                      value="{{number_format($total_stock_sell, 3)}}" bg="success" />
        </div>
        <div class="col-xxl-3 col-sm-3 mt-1">
            <x-widget style="6" link="#" icon="las la-sync f-size--56" title="Total Stock Interest"
                      value="{{number_format($total_stock_interest, 3)}}" bg="danger" />
        </div>
        <div class="col-xxl-3 col-sm-3 mt-1">
            <x-widget style="6" link="#" icon="las la-sync f-size--56" title="Total Stock Exchange"
                      value="{{number_format($total_stock_exchange, 3)}}" bg="danger" />
        </div>
    </div>



    <div class="row mb-none-30 mb-3 align-items-center gy-4">
        <div class="col-md-12 mt-5">
            <h5>COPY TRADE INFORMATION</h5>
        </div>
        <div class="col-xxl-4 col-sm-4 mt-1">
            <x-widget style="6" link="#" icon="las la-sync f-size--56" title="Total Copy Trade Buy"
                      value="{{number_format($total_copy_buy, 3)}}" bg="primary" />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-4 col-sm-4 mt-1">
            <x-widget style="6" link="#" icon="las la-coins f-size--56" title="Total Copy Trade Sell"
                      value="{{number_format($total_copy_sell, 3)}}" bg="success" />
        </div>
        <div class="col-xxl-4 col-sm-4 mt-1">
            <x-widget style="6" link="#" icon="las la-sync f-size--56" title="Total Copy Trade Interest"
                      value="{{number_format($total_copy_interest, 3)}}" bg="danger" />
        </div>
    </div>



@endsection

@push('breadcrumb-plugins')

@endpush


@push('script')

    <script>

        $(document).ready(function (e){

            $('.search-u').select2();
        })

        $(document).on('change', '.search-u', function (e){

            $('#searchF').submit();
        })
    </script>

@endpush



