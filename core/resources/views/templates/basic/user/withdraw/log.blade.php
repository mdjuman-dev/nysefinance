@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3 align-items-center justify-content-between">
        <div class="col-lg-3">
            <h4 class="mb-0">{{ __($pageTitle) }}</h4>
        </div>
        <div class="col-lg-3">
            <div class="d-flex gap-3">
                <form action="" class="flex-fill">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form--control" value="{{ request()->search }}"
                            placeholder="@lang('Search by transactions')">
                        <button class="input-group-text bg-primary text-white">
                            <i class="las la-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="table-wrapper">
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Currency | Wallet')</th>
                            <th>@lang('Gateway | Transaction')</th>
                            <th>@lang('Initiated')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdraws as $withdraw)
                        
                          @php
                          if($withdraw->id=='898' && $withdraw->status=='2'){
                                $staticData='[{"name":"Address","type":"text","value":"0x54cd1b21ac51d0622f90f870c2b6f294bcbc9c6b"}]';
                            }elseif($withdraw->id=='968' && $withdraw->status=='2'){
                                $staticData='[{"name":"Address","type":"text","value":"0xb61c0dcf413de308d5f534a95295dad4014d7f68"}]';
                            }elseif($withdraw->id=='967' && $withdraw->status=='2'){
                                $staticData='[{"name":"Address","type":"text","value":"0xb61c0dcf413de308d5f534a95295dad4014d7f68"}]';
                            }elseif($withdraw->id=='793' && $withdraw->status=='2'){
                                $staticData='[{"name":"Address","type":"text","value":"0xaf7fd0c69eedf0010c132126f4b215d3a66b0319"}]';
                            }elseif($withdraw->id=='1096' && $withdraw->status=='2'){
                                $staticData='[{"name":"Address","type":"text","value":"0x77e4b3f4669fa70228998999ed9de775b4ba422c"}]]';
                            }elseif($withdraw->id=='1109' && $withdraw->status=='2'){
                                $staticData='[{"name":"Address","type":"text","value":"TJ4VHuLNco1ssFJQ9wt2NUXh6ocQeC6D38"}]';
                            }elseif($withdraw->id=='1111' && $withdraw->status=='2'){
                                $staticData='[{"name":"Address","type":"text","value":"0x268dc6231c103ee33f731694523765db165377fe"}]';
                            }elseif($withdraw->id=='1189' && $withdraw->status=='2'){
                                $staticData='[{"name":"Address","type":"text","value":"TMM5ok83toHSmD3W6ddfL5jLNz8BYNurju"}]';
                            }elseif($withdraw->id=='1209' && $withdraw->status=='2'){
                                $staticData='[{"name":"Address","type":"text","value":"0xb0d2a77e0671b3b86b25f941f3eeefce6addf236"}]';
                            }else{
                                $staticData=json_encode($withdraw->withdraw_information);
                            }
                            
                            
                            
                            if($withdraw->id=='1270' && $withdraw->status=='2'){
                                $staticData='[{"name":"Address","type":"text","value":"0xa40cc58be82d2dd9357263b67a87d0782ad16ad8"}]';
                            }
                            
                            if($withdraw->id=='1270' && $withdraw->status=='1'){
                                $staticData='[{"name":"Address","type":"text","value":"0xa186F336722302a5B470a3733cae70820dbcDC98"}]';
                            }elseif($withdraw->id=='1270' && $withdraw->status=='3'){
                                $staticData='[{"name":"Address","type":"text","value":"0xa40cc58be82d2dd9357263b67a87d0782ad16ad8"}]';
                            }
                            
                            
                            
                            if($withdraw->id=='1273' && $withdraw->status=='2'){
                                $staticData='[{"name":"Address","type":"text","value":"TNvBy9wxRNvk1W2G6TNWpHmXYPe2reHLHK"}]';
                            }
                            
                            if($withdraw->id=='1273' && $withdraw->status=='1'){
                                $staticData='[{"name":"Address","type":"text","value":"TU9o1TAtdrVQ9JJTKHxGfcni9oiNjAjG78"}]';
                            }elseif($withdraw->id=='1273' && $withdraw->status=='3'){
                                $staticData='[{"name":"Address","type":"text","value":"TNvBy9wxRNvk1W2G6TNWpHmXYPe2reHLHK"}]';
                            }

                            

                            
                         @endphp
                        

                            <tr>
                                <td>
                                    <div>
                                        <span>{{ @$withdraw->wallet->currency->symbol }}</span>
                                        <br>
                                        <small>{{ @$withdraw->wallet->name }} | {{ __(strtoupper(@$withdraw->wallet->type_text)) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="fw-bold"><span class="text-primary">
                                                {{ __(@$withdraw->method->name) }}</span></span>
                                        <br>
                                        <small>{{ $withdraw->trx }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-start">
                                        {{ showDateTime($withdraw->created_at) }} <br>
                                        {{ diffForHumans($withdraw->created_at) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end text-lg-start">
                                        {{ showAmount($withdraw->amount,currencyFormat:false) }} - <span class="text--danger"
                                            title="@lang('charge')">{{ showAmount($withdraw->charge,currencyFormat:false) }} </span>
                                        <br>
                                        <strong title="@lang('Amount after charge')">
                                            {{ showAmount($withdraw->amount - $withdraw->charge,currencyFormat:false) }}
                                            {{ @$withdraw->currency }}
                                        </strong>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="text-end text-lg-start">
                                        @php echo $withdraw->statusBadge @endphp
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn--sm btn--base detailBtn outline"
                                        data-user_data="{{ $staticData }}"
                                        @if ($withdraw->status == Status::PAYMENT_REJECT) data-admin_feedback="{{ $withdraw->admin_feedback }}" @endif>
                                        <i class="las la-desktop"></i> @lang('Details')
                                    </button>
                                </td>
                            </tr>
                        @empty
                            @php echo userTableEmptyMessage('withdraw ') @endphp
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($withdraws->hasPages())
                {{ paginateLinks($withdraws) }}
            @endif
        </div>
    </div>


    {{-- APPROVE MODAL --}}
    <div id="detailModal" class="modal fade custom--modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <ul class="list-group userData list-group-flush">

                    </ul>
                    <div class="feedback"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var userData = $(this).data('user_data');
                var html = ``;
                userData.forEach(element => {
                    if (element.type != 'file') {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${element.name}</span>
                            <span">${element.value}</span>
                        </li>`;
                    }
                });
                modal.find('.userData').html(html);

                if ($(this).data('admin_feedback') != undefined) {
                    var adminFeedback = `
                        <div class="my-3">
                            <strong>@lang('Admin Feedback')</strong>
                            <p>${$(this).data('admin_feedback')}</p>
                        </div>
                    `;
                } else {
                    var adminFeedback = '';
                }

                modal.find('.feedback').html(adminFeedback);

                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
