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
                                <th>@lang('Amount')</th>
                                <th>@lang('Type')</th>
                                <th>@lang('For')</th>
                                <th>@lang('Remark')</th>
                                <th>@lang('Date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($interests as $interest)
                                <tr>
                                    <td>
                                        @if($interest->user_id)
                                            <a href="{{ route('admin.users.detail', $interest->user_id) }}">
                                                {{$interest->user->fullname}}
                                            </a>
                                        <br>
                                        {{$interest->user->email}}
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td>
                                        {{$interest->amount}} (USDT)
                                    </td>
                                    <td>
                                        Interest
                                    </td>
                                    <td>
                                        @if($interest->user_stock->product)
                                        {{$interest->user_stock->product->name}}
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td>
                                        {{$interest->remark}}
                                    </td>
                                    <td>
                                        {{$interest->created_at->format('d-m-Y h:i A')}}
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
                @if ($interests->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($interests) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    <form action="{{route('admin.stock.interests')}}" method="get">

        <div class="row">
            <div class="col-md-5">
                <select name="user_id" id="user_id" class="form-control">
                    <option value="null">--Choose User--</option>
                    @foreach($users as $user)
                    <option {{request()->get('user_id') && request()->get('user_id')==$user->id?'selected':''}} value="{{$user->id}}">{{$user->email}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-5">
                <input type="text" name="date" id="daterangepicker" autocomplete="off" class="form-control">
            </div>
            <div class="col-md-2">
                <button class="btn btn--success" type="submit">Search..</button>
            </div>
        </div>

    </form>
@endpush

@push('style')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


@endpush

@push('script')

{{--    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>--}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>

        $(document).ready(function (e){
            $('#user_id').select2({
                multiple:false,
                placeholder:'--Choose User--'
            });



            $('#daterangepicker').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#daterangepicker').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            });

            $('#daterangepicker').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        })
    </script>

@endpush
