
@extends($activeTemplate.'layouts.app')
@section('main-content')
@php
$content = getContent('account_verification.content',true);
@endphp
<section class="account">
    <div class="account-inner">

        <div class="account-right-wrapper">
            <div class="account-right account-right-custom">
                <div class="account-content">
                    <div class="account-form">
                        <h3 class="account-form__title mb-0">@lang('2FA Verification')</h3>
                        <p class="account-form__desc">
                            @lang('Strengthen account security with a unique code from your authenticator app or SMS')
                        </p>
                        <form action="{{route('user.2fa.verify')}}" method="POST" class="submit-form">
                            @csrf
                            @include($activeTemplate.'partials.verification_code')
                            <div class="form--group">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

