@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">


                    <form action="">
                        <div class="row p-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Search By Email</label>
                                    <select name="email" class="form-control select2">
                                        <option value="">--Email--</option>
                                        @foreach($users as $user)
                                            <option {{request()->get('email')==$user->id?'selected':''}} value="{{$user->id}}">{{$user->email}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Search By UID</label>
                                    <select name="uid" class="form-control select2">
                                        <option value="">--UID--</option>
                                        @foreach($users as $user)
                                            <option {{request()->get('uid')==$user->id?'selected':''}} value="{{$user->id}}">{{$user->uid}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mt-4 pt-2">
                                    <button class="btn btn-success" type="submit">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Stock Info')</th>
                                <th>@lang('TRXID')</th>
                                <th>@lang('From')</th>
                                <th>@lang('Created At')</th>
                                <th>@lang('Approved At')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sellRequests as $sellRequest)

                                <tr>
                                    <td>
                                        {{$sellRequest->user->fullname}}
                                        <br>
                                        <a href="{{ route('admin.users.detail', $sellRequest->user_id) }}">
                                            <span>@</span>{{$sellRequest->user->email}}</a>

                                    </td>
                                    <td>
                                        <div>
                                            <strong>Stock:</strong>
                                            {{$sellRequest->product->name}}
                                        </div>
                                        <div>
                                            <strong>Stock Type:</strong>
                                            @if($sellRequest->user_stock->type=='fix')
                                                Mutual
                                            @else
                                                Live Market
                                            @endif
                                        </div>
                                        <div>
                                            <strong>Invest Amount:</strong>
                                            {{$sellRequest->user_stock->invest_amount}}
                                        </div>
                                        <div>
                                            <strong>Charge (flat):</strong>
                                            {{$sellRequest->charge}}
                                        </div>
                                    </td>

                                    <td>
                                        {{isset($sellRequest->trx_id)?$sellRequest->trx_id:'N/A'}}
                                    </td>


                                    <td>
                                        @if($sellRequest->product->use_for=='bond')
                                            <span class="badge badge--success">BOND</span>
                                        @else
                                            <span class="badge badge--info">Stock</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{$sellRequest->created_at->format('Y-m-d H:i:s A')}}
                                    </td>
                                    <td>
                                        @if($sellRequest->status=='approved')
                                            {{$sellRequest->created_at->format('Y-m-d H:i:s A')}}
                                        @else
                                            ...
                                        @endif
                                    </td>

                                    <td>
                                        @if($sellRequest->status=='pending')
                                        <button class="btn btn-success requestStatus" data-amount="{{$sellRequest->user_stock->invest_amount}}" data-status="approve"
                                                data-id="{{$sellRequest->id}}" type="button">Approve
                                        </button>
                                        <button class="btn btn-danger requestStatus" data-status="reject"
                                                data-id="{{$sellRequest->id}}" type="button">Reject
                                        </button>
                                        @elseif($sellRequest->status=='approved')
                                        <button class="btn btn-success disabled" disabled="disabled">Approved</button>
                                        @else
                                        <button class="btn btn-danger disabled" disabled="disabled">Rejected</button>
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
                @if ($sellRequests->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($sellRequests) }}
                    </div>
                @endif
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('admin.stock.sell.status')}}" method="post">
                    @csrf
                    <input type="hidden" name="request_id">
                    <input type="hidden" name="request_status">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLongTitle">Confirm</h6>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h6>Are you sure you want to <span class="request_status"></span> this sell request?</h6>

                        <div class="form-group charge-section mt-3 d-none">
                            <label for="">Change(flat)</label>
                            <input type="text" class="form-control charge_amount" name="charge" placeholder="Enter charge amount">
                        </div>
                        <div class="form-group" style="padding: 10px 20px;background: #f7f7f7; border-radius: 5px;font-size: 15px;">
                            <div>
                                <strong>Invest Amount:</strong> <span class="invest_amount">0.00</span> USD
                            </div>
                            <div>
                                <strong>Charge:</strong> <span class="charge_amount">0.00</span> USD
                            </div>
                            <div>
                                <strong>User Will Get:</strong> <span class="get_amount">0.00</span> USD
                            </div>
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

@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.stock.index')}}" class="float-right btn btn-primary">Back</a>
@endpush

@push('script')
    <script>
        $(document).on('keyup or paste', '.charge_amount', function (e) {

            const amount=parseFloat($(this).attr('data-amount'));
            const charge=$(this).val();

            if(amount){
                let percent=(amount * charge) / 100;
                percent=parseFloat(percent);
                let grand_amount=amount - percent;

                $('.invest_amount').text(amount);
                $('.charge_amount').text(charge);
                $('.get_amount').text(grand_amount);
            }

            // invest_amount
            // charge_amount
            // get_amount
        });


        $(document).on('click', '.requestStatus', function (e) {

            const id = $(this).attr('data-id');
            const status = $(this).attr('data-status');
            const amount = $(this).attr('data-amount');

            $('.invest_amount').text(amount);

            $('input[name=request_id]').val(id);
            $('input[name=request_status]').val(status);
            $('.charge_amount').attr('data-amount', amount);

            if(status=='approve'){
                $('.charge-section').removeClass('d-none');
            }else{
                $('.charge-section').addClass('d-none');
            }

            $('.request_status').text(status)

            $('#statusModal').modal('show');

        });
    </script>
@endpush
