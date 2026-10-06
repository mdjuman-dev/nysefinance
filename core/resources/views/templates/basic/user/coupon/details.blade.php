@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <div class="row">


                            <div class="col-md-4 col-12 main-coupon-sec">
                                <div class="section-coupon">
                                    <div class="section-icon">
                                        <img src="{{ getImage(getFilePath('currency') .'/'.$coupon->icon,getFileSize('currency')) }}" >
                                    </div>
                                    <div class="section-details">
                                        <div class="sec-name">
                                            <h5 class="mb-0">{{$coupon->name}}</h5>
                                        </div>
                                        <div class="sec-name">
                                            $<b>{{$coupon->price}}</b>
                                        </div>
                                        <div class="sec-name">
                                            <small>Wining Date {{$coupon->wining_date}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="col-md-8 col-12">
                            @if(!$userCoupon)
                            <button class="btn btn-success d-block w-100 buyCoupon mt-3" type="button">
                                Buy Coupon
                            </button>
                            @endif
                            <div class="pt-3 pb-5">
                                {!! $coupon->description !!}
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="buyCouponModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Confirmation</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('user.coupon.buy')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$coupon->id}}">
                    <div class="modal-body">
                        <div class="form-group mb-2 mt-2">
                            <div class="alert alert-danger">
                                Total Sold: <b>{{ $coupon->total_buy_amount }}</b> USD<br>
                                Users Joined: <b>{{ $coupon->total_buy }}</b> people
                            </div>
                        </div>
                        <h5>Are you sure you want to buy <b>{{$coupon->name}}</b> coupon ?</h5>

                        <div class="form-group mt-3">
                            <label for="">Coupon Quantity</label>
                            <input type="text" name="quantity" class="form-control" value="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


@endsection

@push('topContent')
    <div class="d-flex justify-content-between">
        <h4 class="mb-4">{{ __($pageTitle) }}</h4>

        <div>
            <a href="{{route('user.coupons')}}" class="btn btn-sm btn-danger">JackPlay</a>
        </div>
    </div>
@endpush

@push('script-lib')

@endpush
@push('style-lib')

    <style>
        .section-coupon{
            display: flex;
        }
        .section-icon img{
            border-radius: 50px;
            padding: 5px;
        }
        .section-icon{
            width: 70px;
            height: 50px;
        }
        .main-coupon-sec{
            background: #6c6c6c;
            padding: 10px;
            border-radius: 5px;
        }
        .section-details{
            color: white;
            padding-left: 10px;
        }
    </style>

@endpush


@push('script')

    <script>
        $(document).on('click', '.buyCoupon', function (e){

            $('#buyCouponModal').modal('show');
        })


    </script>

@endpush
