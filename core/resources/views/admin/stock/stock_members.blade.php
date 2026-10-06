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
                                        <option {{request()->get('status')=='pending'?'selected':''}} value="pending">Pending</option>
                                        <option {{request()->get('status')=='approve'?'selected':''}} value="approve">Approve</option>
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
                                <th>@lang('Amount')</th>
                                <th>@lang('Created At')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($stock_members as $stock_member)

                                <tr>
                                    <td>
                                        <a href="{{ route('admin.users.detail', $stock_member->user_id) }}">
                                            {{$stock_member->user->fullname}}
                                        </a>
                                        <br>
                                        {{$stock_member->user->email}}
                                    </td>
                                    <td>{{$stock_member->amount}}</td>
                                    <td>{{$stock_member->created_at->format('d-m-Y H:i:s')}}</td>
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
                @if ($stock_members->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($stock_members) }}
                    </div>
                @endif
            </div>
        </div>
    </div>



    <!-- Modal -->

@endsection

@push('breadcrumb-plugins')

@endpush

@push('script')



@endpush
