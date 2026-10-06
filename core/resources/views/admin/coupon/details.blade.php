@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="justify-content-end mb-3 mt-2" style="text-align: right">
                        <button type="button" class="btn btn-sm btn-success markLose">
                            Mark All Close
                        </button>
                    </div>
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Coupon')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Win date')</th>
                                <th>@lang('Win Price')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($user_coupons as $user_coupon)

                                <tr>
                                    <td>{{$user_coupon->user->firstname.' '.$user_coupon->user->lastname}}</td>
                                    <td>${{$user_coupon->price}}</td>
                                    <td>{{$user_coupon->coupon->name}}</td>
                                    <td>
                                        @if($user_coupon->status=='pending')
                                            <span class="badge badge--danger">Pending</span>
                                        @elseif($user_coupon->status=='win')
                                            <span class="badge badge--success">
                                                Win
                                            </span>
                                        @else
                                            <span class="badge badge--danger">Lose</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{$user_coupon->wining_date}}
                                    </td>
                                    <td>
                                        <div>
                                            Win Price: {{$user_coupon->win_price}}
                                        </div>
                                        <div>
                                            {{$user_coupon->details?$user_coupon->details:'N/A'}}
                                        </div>
                                    </td>
                                    <td>
                                        @if($user_coupon->status=='pending')
                                        <button type="button" class="btn btn-sm btn-success markWin" data-id="{{$user_coupon->id}}">
                                            Mark As Win
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-sm disabled {{$user_coupon->status=='pending'?'btn-success':'btn-danger'}}">
                                            {{ucwords($user_coupon->status)}}
                                        </button>
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



    <!-- Modal -->
    <div class="modal fade" id="markWinEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Confirmation</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{route('admin.coupon.win')}}" method="post">
                    @csrf
                    <input type="hidden" name="coupon_id" class="coupon_id" value="{{$coupon->id}}">
                    <input type="hidden" name="user_coupon_id" class="user_coupon_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Enter Win Price</label>
                            <input type="text" name="win_price" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Details</label>
                            <input type="text" name="details" class="form-control" placeholder="Enter details">
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Continue</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="markLoseEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Confirmation</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{route('admin.coupon.lose')}}" method="post">
                    @csrf
                    <input type="hidden" name="coupon_id" class="coupon_id" value="{{$coupon->id}}">
                    <div class="modal-body">
                        <div class="p-3">
                            Are you sure you want to close all coupons?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Continue</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.coupon')}}" class="float-right btn btn-primary">Back</a>
@endpush


@push('breadcrumb-plugins')

@endpush

@push('script')

    <script>
        $(document).on('click', '.markWin', function (e){

            const user_coupon_id=$(this).attr('data-id');

            $('.user_coupon_id').val(user_coupon_id);

            $('#markWinEdit').modal('show');

        });

        $(document).on('click', '.markLose', function (e){

            $('#markLoseEdit').modal('show');

        });
    </script>

@endpush
