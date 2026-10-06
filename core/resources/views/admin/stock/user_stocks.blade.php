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
                                <div class="form-group">
                                    <label for="">Search By Status</label>
                                    <select name="status" class="form-control select2">
                                        <option value="">--Status--</option>
                                        <option {{request()->get('status')=='active'?'selected':''}} value="active">Active</option>
                                        <option {{request()->get('status')=='inactive'?'selected':''}} value="inactive">Inactive</option>
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
                                    <th>@lang('Status')</th>
                                    <th>@lang('Interest')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($custom_interests as $custom_interest)

                                    <tr>
                                        <td>
                                            <p>Name: {{$custom_interest->user->fullname}}</p>
                                            <p>Email: {{$custom_interest->user->email}}</p>
                                            <p>Username: <a href="{{ route('admin.users.detail', $custom_interest->user_id) }}">
                                                {{$custom_interest->user->username}}
                                                </a>
                                            </p>
                                        </td>

                                        <td>

                                            @if($custom_interest->status=='active')
                                                <span class="badge badge--success">Active</span>
                                            @else
                                                <span class="badge badge--danger">Inactive</span>
                                            @endif

                                        </td>

                                        <td>
                                            {{$custom_interest->interest}}
                                        </td>
                                        <td>

                                            <button type="button" data-user-id="{{$custom_interest->user_id}}" data-status="{{$custom_interest->status}}" data-id="{{$custom_interest->id}}"
                                                    data-interest="{{$custom_interest->interest}}"
                                                    class="btn btn-info edit-stock">Edit</button>

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
                @if ($custom_interests->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($custom_interests) }}
                    </div>
                @endif
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="editUserStockModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <form action="{{route('admin.stock.update.user.stocks')}}" method="post">
                    @csrf

                    <input type="hidden" name="user_id" class="user_id">
                    <input type="hidden" name="id" class="id">


                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit User Stock</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Status</label>
                            <select name="status" id="status_custom_interest" class="form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Interest</label>
                            <input type="text" name="interest" class="form-control interest">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>
            </div>
        </div>
    </div>



@endsection

@push('breadcrumb-plugins')
    <a href="{{route('admin.stock.sell.request')}}" class="float-right btn btn-success">Sell Request</a>
    <a href="{{route('admin.stock.create')}}" class="float-right btn btn-primary">New Stock</a>
@endpush

@push('script')

    <script>
        $(document).on('click', '.edit-stock', function (e){
            e.preventDefault();

            const user_id=$(this).attr('data-user-id');
            const id=$(this).attr('data-id');
            const status=$(this).attr('data-status');
            const interest=$(this).attr('data-interest');


            $('.id').val(id);
            $('#status_custom_interest').val(status).trigger('change');
            $('.interest').val(interest);
            $('.user_id').val(user_id);

            $('#editUserStockModal').modal('show');


        })
    </script>
@endpush
