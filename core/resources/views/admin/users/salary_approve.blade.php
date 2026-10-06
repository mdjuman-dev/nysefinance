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
                                <th>@lang('Invest')</th>
                                <th>@lang('Others')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($salaries as $key=>$salary)
                                <tr>
                                    <td>
                                        {{++$key}}
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $salary->user->fullname }}</span>
                                        <br>
                                        <span class="small">
                                                <a href="{{ route('admin.users.detail', $salary->user->id) }}"><span>@</span>{{ $salary->user->username }}</a>
                                            </span>
                                    </td>


                                    <td>
                                        {{$salary->user->username}}
                                    </td>
                                    <td>
                                        {{ $salary->user->email }}<br>{{ $salary->user->mobileNumber }}
                                    </td>



                                    <td>
                                        {{$salary->amount}}
                                    </td>
                                    <td>

                                        @if($salary->others)
                                            @foreach(json_decode($salary->others) as $key=>$ot)
                                                <div>
                                                    <b>{{ucwords(str_replace('_',' ', $key))}}</b>:  ${{$ot}}
                                                </div>
                                            @endforeach
                                        @endif

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
                @if ($salaries->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($salaries) }}
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="groupExpertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('admin.approve.salary')}}" method="post" id="appGroupExpert">
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
                            <h5>Are you sure you want to approve this user salary ?</h5>
                        </div>

                        <div class="form-group mt-3">
                            <label for="">Salary Label</label>
                            <input type="text" name="label" placeholder="Enter Salary Label">
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
<a href="{{route('admin.get.salary')}}" class="btn btn-primary">Back</a>

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
