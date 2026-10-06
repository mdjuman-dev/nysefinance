@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3 align-items-center justify-content-between">
        <div class="col-lg-3">
            <h4 class="mb-0">{{ __($pageTitle) }}</h4>
        </div>
        <div class="col-lg-3">

        </div>
        <div class="col-lg-12">
            <div class="table-wrapper">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>#SL</th>
                            <th>Amount</th>
                            <th>@lang('Method')</th>
                            <th>@lang('Charge')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deposits as $key=>$deposit)

                            <tr>
                                <td>
                                    #{{++$key}}
                                </td>
                                <td>
                                    <div>
                                        {{ showAmount($deposit->amount,currencyFormat:false) }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{$deposit->method_currency}}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-start">
                                        {{ showAmount($deposit->charge,currencyFormat:false) }}
                                    </div>
                                </td>
                                <td class="text-center">

                                    @if($deposit->status=='1')
                                        <span class="badge badge--success">Success</span>
                                    @elseif($deposit->status=='2')
                                        <span class="badge badge--info">Pending</span>
                                    @else
                                        <span class="badge badge--danger">Rejected</span>
                                    @endif

                                </td>
                                <td>
                                    <a href="{{route('user.deposit.ch',[$deposit->id])}}" {{$deposit->status=='pending'?'':'disabled'}} target="_blank"
                                       class="btn btn-sm btn-info {{$deposit->status=='pending'?'':'disabled'}}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            No Data Found
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($deposits->hasPages())
                {{ paginateLinks($deposits) }}
            @endif
        </div>
    </div>



@endsection

@push('script')


@endpush
