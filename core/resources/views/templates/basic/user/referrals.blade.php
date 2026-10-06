@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="row gy-4">
    <div class="col-12">
        <h4 class="mb-0">{{ __($pageTitle) }}
            @if(auth()->user()->group_expert && auth()->user()->group_expert=='yes')
                <span></span>
            @else
            <small style="float: right;font-size: 17px;color: #e7e7e7 !important;font-weight: 600;">Sign-Up Bonus $60</small>
            @endif

        </h4>
    </div>
    @if ($user->referrer)
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4>@lang('You are referred by') <span class="text--base">{{ @$user->referrer->fullname }}</span></h4>
            </div>
        </div>
    </div>
    @endif
    <div class="col-md-12">
        @if ($user->allReferrals->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="text-start"> @lang('Users Referred By Me') &nbsp;( {{ $user->username }} ) <small class="ml-2">Invest: {{$user_invest}}USD | M: {{$user->allReferrals->count()}}</small></h5>
                </div>
                <div class="card-body">
                    <div class="treeview-container">
                        <ul class="treeview">
                            <li class="items-expanded">
                                {{ $user->fullname }}
                                @if($stock_member_verified)
                                &nbsp;&nbsp;&nbsp;&nbsp;<i style=" color: #00ff0b !important;padding: 5px 4px 2px 4px; border-radius: 5px;font-size: 13px;" class="fa fa-certificate"></i>
                                @endif
                                &nbsp;
                                @include($activeTemplate . 'partials.under_tree', [
                                    'user'    => $user,
                                    'layer'   => 0,
                                    'isFirst' => true,
                                ])
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
        <div class="card">
            <div class="card-body p-5">
                <div class="empty-thumb text-center">
                    <img src="{{ asset('assets/images/extra_images/empty.png') }}" />
                    <p class="fs-14">@lang('No data found')</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'dashboard/css/jquery.treeView.css') }}">

    <style>
        .redirect_child:hover{
            color: rgb(14, 204, 14);
        }
        .redirect_child{
            color: rgb(14, 204, 14);
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'dashboard/js/jquery.treeView.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.treeview').treeView();
        })(jQuery);

        $(document).on('click', '.redirect_child', function(e){
            const url=$(this).attr('data-url');

            if(url){
                location.href.url;
            }
        })
    </script>
@endpush

