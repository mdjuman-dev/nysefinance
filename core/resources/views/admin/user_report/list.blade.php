@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('From User')</th>
                                    <th>@lang('To User')</th>
                                    <th>@lang('Details')</th>
                                    <th>@lang('User')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports as $report)
                                    <tr>
                                        <td>
                                            @if($report->type=='p2p')
                                                <span class="badge badge--success">P2P</span>
                                            @elseif($report->type=='withdraw')
                                                <span class="badge badge--danger">Withdraw</span>
                                            @elseif($report->type=='wrong_password')
                                                <span class="badge badge--danger">Attempt 3 times Wrong password</span>
                                            @elseif($report->type=='forget_password')
                                                <span class="badge badge--danger">Forget Password</span>
                                            @elseif($report->type=='transfer')
                                                <span class="badge badge--info">Transfer</span>
                                            @endif

                                        </td>
                                        <td>


                                                <div class="btn-group">

                                                    @if($report->status=='solved')
                                                        <button type="button" class="btn btn-success dropdown-toggle disabled" disabled data-bs-toggle="dropdown">Solved</button>
                                                    @elseif($report->status=='review')
                                                        <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown">Reviewing</button>
                                                    @elseif($report->status=='pending')
                                                        <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown">Pending</button>
                                                    @endif



                                                    <div class="dropdown-menu" role="menu">

                                                        @if($report->status=='review')
                                                            <a class="dropdown-item statusUpdate" data-id="{{$report->id}}" data-type="solved">Solved</a>
                                                        @elseif($report->status=='pending')
                                                            <a class="dropdown-item statusUpdate" data-id="{{$report->id}}" data-type="solved" >Solved</a>
                                                            <a class="dropdown-item statusUpdate" data-id="{{$report->id}}" data-type="review" >Review</a>
                                                        @endif

                                                    </div>
                                                </div>



                                        </td>
                                        <td>
                                            <div class="text-left" style="text-align: left !important;">
                                                <a href="{{ route('admin.users.detail', $report->user_id) }}">
                                                    {{$report->user->username}}
                                                </a>

                                                <br>
                                                {{$report->user->email}}
                                            </div>
                                        </td>

                                        <td>
                                            @if($report->repoted_user_id)
                                            <div class="text-left" style="text-align: left !important;">
                                                <a href="{{ route('admin.users.detail', $report->repoted_user_id) }}">
                                                    {{$report->reported_user->username}}
                                                </a>

                                                <br>
                                                {{$report->reported_user->email}}
                                            </div>
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <td>
                                            {{$report->details}}
                                        </td>
                                        <td>
                                            @if($report->type=='withdraw')
                                                <a href="{{route('admin.withdraw.data.details',[$report->ref_id])}}" class="btn btn-sm btn-success">Details</a>
                                            @elseif($report->type=='p2p')
                                                <a target="_blank" href="{{route('admin.p2p.trade.details',[$report->ref_id])}}" class="btn btn-sm btn-success">Details</a>
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
                @if ($reports->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($reports) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">@lang('Delete Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form  method="post"  action="{{route('admin.user.report.status')}}">
                    @csrf

                    <input type="hidden" name="id">
                    <input type="hidden" name="status">

                <div class="modal-body">
                        <p>@lang('Are you sure you want to change report status') <span class="status"></span>?</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.faq.create') }}" class="btn btn-sm btn-outline--primary">
        <i class="la la-plus"></i>@lang('Add New')
    </a>
@endpush

@push('script')
    <script>
            "use strict";

            $(document).on('click','.statusUpdate', function(e) {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-type');


                $('input[name=id]').val(id);
                $('input[name=status]').val(status);
                $('.status').text(status)

                $('#statusModal').modal('show');
                // modal.modal('show');
            });


    </script>
@endpush
