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
                                    <th>@lang('Expert')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            @if($user->group_expert_id)
                                                <b>{{$user->group_expert_id}}</b>
                                            @else
                                                <b>N/A</b>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                            </span>
                                        </td>


                                        <td>
                                            {{$user->username}}
                                        </td>
                                        <td>
                                            {{ $user->email }}<br>{{ $user->mobileNumber }}
                                        </td>



                                        <td>
                                            <div class="button--group">
                                                @if($user->group_expert=='yes')
                                                    <button type="button"  class="btn btn-success" disabled="disabled">
                                                        Approved
                                                    </button>
                                                @else
                                                <button type="button" data-id="{{$user->id}}" class="btn btn-success approve-group-expert">
                                                    Approve
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
                @if ($users->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($users) }}
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="groupExpertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('admin.assign.group.expert')}}" method="post" id="appGroupExpert">
                    @csrf
                    <input type="hidden" name="user_id" class="user_id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <h5>Are you sure you want to approve this user as a group expert ?</h5>
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
            $('.user_id').val(user_id);

            $('#groupExpertModal').modal('show');
        });
        $(document).on('click', '.cnf-btn', function(e){

            $(this).attr('disabled', 'disabled');

            $('#appGroupExpert').submit();
        });

    </script>
@endpush
