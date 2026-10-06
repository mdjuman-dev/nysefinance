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
                                    <th>#SL</th>
                                    <th>@lang('User')</th>
                                    <th>Username</th>
                                    <th>@lang('Email-Mobile')</th>
                                    <th>@lang('Reject Reason')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reset_requests as $key=>$reset_request)
                                    <tr>
                                        <td>
                                            #{{++$key}}
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $reset_request->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $reset_request->id) }}"><span>@</span>{{ $reset_request->user->username }}</a>
                                            </span>
                                        </td>


                                        <td>
                                            {{$reset_request->user->username}}
                                        </td>
                                        <td>
                                            {{ $reset_request->user->email }}<br>{{ $reset_request->user->mobileNumber }}
                                        </td>

                                        <td>
                                         @if($reset_request->reason)
                                            {{ $reset_request->reason }}
                                        @else
                                            ..//..
                                        @endif
                                        </td>



                                        <td>
                                            <div class="button--group">
                                                @if($reset_request->status=='approved')
                                                    <button type="button"  class="btn btn-success" disabled="disabled">
                                                        Approved
                                                    </button>
                                                @else

                                                <button type="button" data-status="approved" data-id="{{$reset_request->id}}" class="btn btn-success approve-group-expert">
                                                    Approve
                                                </button>

                                                <button type="button" data-status="reected" data-id="{{$reset_request->id}}" class="btn btn-danger approve-group-expert">
                                                    Reject
                                                </button>
                                                @endif
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($reset_requests->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($reset_requests) }}
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="groupExpertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('admin.reset.request.status')}}" method="post" id="appGroupExpert">
                    @csrf
                    <input type="hidden" name="id" class="user_id">
                    <input type="hidden" name="status" class="status">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <h5 class="warn_msg"></h5>
                        </div>

                        <div class="form-group reject-section d-none mt-3">
                            <label for="">Enter Reject Reason</label>
                            <input type="text" class="form-control form--control" name="reason" placeholder="Enter Reject Reason..">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary cnf-btn">Confirm</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush

@push('script')
    <script>
        $(document).on('click', '.approve-group-expert', function(e){

            const user_id=$(this).attr('data-id');
            const status=$(this).attr('data-status');

            if(status=='approved'){
                $('.warn_msg').text('Are you sure you want to approve reset security pin?');
                $('.reject-section').addClass('d-none');
            }else{
                $('.warn_msg').text('Are you sure you want to reject reset security pin?');
                $('.reject-section').removeClass('d-none');
            }

            $('.user_id').val(user_id);
            $('.status').val(status);

            $('#groupExpertModal').modal('show');
        });

        $(document).on('click', '.cnf-btn', function(e){

            $(this).attr('disabled', 'disabled');

            $('#appGroupExpert').submit();
        });

    </script>
@endpush
