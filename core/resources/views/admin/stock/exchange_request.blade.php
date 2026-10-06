@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Stock Info')</th>
                                <th>@lang('Broker')</th>
                                <th>@lang('Others Info')</th>

                                @if(request()->get('type') && request()->get('type')=='approved')
                                    <th>@lang('Updated At')</th>
                                @else
                                    <th>@lang('Created At')</th>
                                @endif

                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($exchangeRequests as $exchangeRequest)

                                <tr>
                                    <td>
                                        {{$exchangeRequest->user->fullname}}
                                        <br>
                                        <a href="{{ route('admin.users.detail', $exchangeRequest->user_id) }}"><span>@</span>{{ $exchangeRequest->user->username }}</a>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>Stock:</strong>
                                            {{$exchangeRequest->product->name}}
                                        </div>
                                        <div>
                                            <strong>Stock Type:</strong>
                                            @if($exchangeRequest->user_stock->type=='fix')
                                                Mutual
                                            @else
                                                Live Market
                                            @endif
                                        </div>
                                        <div>
                                            <strong>Invest Amount:</strong>
                                            {{$exchangeRequest->user_stock->invest_amount}}
                                        </div>
                                        <div>
                                            <strong>Charge:</strong>
                                            ${{$exchangeRequest->charge}}
                                        </div>
                                    </td>

                                    <td>
                                        {{$exchangeRequest->broker}}
                                    </td>
                                    <td>
                                        @if(isset($exchangeRequest->others) && $exchangeRequest->others)
                                            @php $others_val=json_decode($exchangeRequest->others);  @endphp

                                                <div>
                                                    <strong>Comment : </strong> {{isset($others_val->comment)?$others_val->comment:''}}
                                                </div>

                                                <div>
                                                    <strong>Receiver Name : </strong> {{isset($others_val->receive_amount)?$others_val->receive_amount:''}}
                                                </div>

                                                <div>
                                                    <strong>Contact Email : </strong> {{isset($others_val->contact_email)?$others_val->contact_email:''}}
                                                </div>
                                        @endif

                                    </td>

                                    <td>

                                        @if(request()->get('type') && request()->get('type')=='approved')
                                            {{$exchangeRequest->updated_at->format('Y-m-d H:i:s A')}}
                                        @else
                                            {{$exchangeRequest->created_at->format('Y-m-d H:i:s A')}}
                                        @endif

                                    </td>


                                    <td>
                                        @if($exchangeRequest->status=='pending')
                                        <button class="btn btn-success requestStatus" data-amount="{{$exchangeRequest->user_stock->invest_amount}}" data-status="approve"
                                                data-id="{{$exchangeRequest->id}}" type="button">Approve
                                        </button>
                                        <button class="btn btn-danger requestStatus" data-status="reject"
                                                data-id="{{$exchangeRequest->id}}" type="button">Reject
                                        </button>
                                        @elseif($exchangeRequest->status=='approved')
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
                @if ($exchangeRequests->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($exchangeRequests) }}
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
                <form action="{{route('admin.stock.exchange.status')}}" method="post">
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
                        <h6>Are you sure you want to <strong class="request_status text-danger "></strong> this exchange request?</h6>


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
    <a href="{{route('admin.stock.exchange.request',['type'=>'approved'])}}" class="float-right btn btn-primary">Approve Request</a>
@endpush

@push('script')
    <script>
        $(document).on('keyup or paste', '.charge_amount', function (e) {

            const amount=parseFloat($(this).attr('data-amount'));
            const charge=$(this).val();

            if(amount){
                let grand_amount=amount - charge;

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
