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
