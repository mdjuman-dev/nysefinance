@extends('admin.layouts.app')
@section('panel')
    <div class="row">

        <div class="col-md-12 mb-5">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link custom-tab-btn" href="{{route('admin.trx',['user_id'=>request()->get('user_id')])}}">Transactions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link custom-tab-btn" href="{{route('admin.stock.trx',['user_id'=>request()->get('user_id')])}}">Stock Transaction</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link custom-tab-btn active" href="{{route('admin.coin.stack.trx',['user_id'=>request()->get('user_id')])}}">Coin Transaction</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link custom-tab-btn" href="{{route('admin.copy.trx',['user_id'=>request()->get('user_id')])}}">Copy Trade Transaction</a>
                </li>
            </ul>
        </div>


        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table--light style--two table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Symbol')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Invest')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coin_stacks as $coin_stack)
                                    <tr>
                                        <td>

                                            <p>Name: {{$coin_stack->user->fullname}}</p>
                                            <p>Email: {{$coin_stack->user->email}}</p>
                                            <p>Username: {{$coin_stack->user->username}}</p>

                                        </td>
                                        <td>{{ $coin_stack->pool->name }}</td>
                                        <td>{{ $coin_stack->pool->symbol }}</td>
                                        <td>${{ $coin_stack->price }}</td>
                                        <td>${{ $coin_stack->invest_amount }}</td>

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
                @if ($coin_stacks->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($coin_stacks) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
@endsection

@push('breadcrumb-plugins')

@endpush


@push('style')


    <style>
        .custom-tab-btn.active{
            background: #071151 !important;
            color: white !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.deleteBtn').on('click', function() {
                var modal = $('#deleteModal');
                var url = $(this).attr('data-url');

                $('#deleteForm').attr('action', url);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
