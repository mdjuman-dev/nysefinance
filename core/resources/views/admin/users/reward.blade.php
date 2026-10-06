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
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rewards as $key=>$reward)
                                    <tr>
                                        <td>
                                            {{++$key}}
                                        </td>
                                        <td>
                                            <span class="fw-bold">{{ $reward->user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $reward->user->id) }}"><span>@</span>{{ $reward->user->username }}</a>
                                            </span>
                                        </td>


                                        <td>
                                            {{$reward->user->username}}
                                        </td>
                                        <td>
                                            {{ $reward->user->email }}<br>{{ $reward->user->mobileNumber }}
                                        </td>



                                        <td>
                                            {{$reward->coin}}
                                        </td>


                                        <td>
                                            <button type="button" data-coin="{{$reward->coin}}" data-reward="{{$reward->id}}" data-id="{{$reward->user_id}}" class="btn btn-success update_reward btn-sm">
                                                Update
                                            </button>

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
                @if ($rewards->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($rewards) }}
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="updateRewardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('admin.reward.update')}}" method="post" id="appGroupExpert">
                    @csrf
                    <input type="hidden" name="user_id" class="user_id">
                    <input type="hidden" name="reward_id" class="reward_id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Confirmation</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Reward</label>
                            <input type="text" class="form-control reward_amount" name="reward_amount" >
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary cnf-btn">Confirm</button>
                    </div>

                </form>
            </div>
        </div>
    </div>


@endsection

@push('breadcrumb-plugins')
   <a href="{{route('admin.salary.approved')}}" class="btn btn-primary">Approve List</a>
@endpush

@push('script')
    <script>
        $(document).on('click', '.update_reward', function(e){

            const user_id=$(this).attr('data-id');
            const reward_id=$(this).attr('data-reward');
            const coin=$(this).attr('data-coin');


            $('.user_id').val(user_id);
            $('.reward_id').val(reward_id);
            $('.reward_amount').val(coin);



            $('#updateRewardModal').modal('show');
        });
        $(document).on('click', '.cnf-btn', function(e){

            $(this).attr('disabled', 'disabled');

            $('#appGroupExpert').submit();
        });

    </script>
@endpush
