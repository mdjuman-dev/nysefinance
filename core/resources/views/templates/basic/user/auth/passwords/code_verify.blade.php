@extends($activeTemplate.'layouts.app')
@section('main-content')
    @php
        $content = getContent('account_recovery.content',true);
    @endphp

    @php
        $languages = App\Models\Language::get();
        $langDetails = $languages->where('code', config('app.locale'))->first();
        $credentials = gs('socialite_credentials');
    @endphp

    <div class="container">

        <div class="col-md-12 mx-auto">
            <div class="account-content__top">
                <div class="account-content__member gap-2">
                    <p class="account-content__member-text"> @lang('Already have an account')? </p>
                    <a href="{{ route('user.login') }}" class="account-link"> @lang('Sign In') </a>
                    @if (gs('multi_language'))
                        <div class="custom--dropdown">
                            <div class="custom--dropdown__selected dropdown-list__item">
                                <div class="thumb">
                                    <img
                                        src="{{ getImage(getFilePath('language') . '/' . @$langDetails->flag, getFileSize('language')) }}">
                                </div>
                                <span class="text">{{ __(@$langDetails->name) }}</span>
                            </div>
                            <ul class="dropdown-list">
                                @foreach ($languages as $language)
                                    <li class="dropdown-list__item change-lang "
                                        data-code="{{ @$language->code }}">
                                        <div class="thumb">
                                            <img
                                                src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
                                        </div>
                                        <span class="text">{{ __(@$language->name) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row col-md-4 mx-auto">

            <div class="account-content">
                <div class="account-form">
                    <div class="card mt-5" style="    background: #2e2e2ec7 !important;">
                        <div class="card-body mt-3">
                            <h3 class="account-form__title mb-0">@lang('Verify Email Address')</h3>
                            <p class="account-form__desc">
                                @lang('A 6 digit verification code sent to your email address')
                                : {{ showEmailAddress($email) }}
                            </p>
                            <form action="{{ route('user.password.verify.code') }}" method="POST" class="submit-form">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                @include($activeTemplate.'partials.verification_code')
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                </div>
                                <div class="form-group">
                                    @lang('Please check including your Junk/Spam Folder. if not found, you can')
                                    <a class="text--base"
                                       href="{{ route('user.password.request') }}">@lang('Try to send again')</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="col-md-12 mx-auto">
            <div class="row gy-3 mt-auto">
                <div class="col-md-6">
                    <div class="bottom-footer__text"> @php echo copyRightText(); @endphp</div>
                </div>
            </div>
        </div>

    </div>

@endsection



@push('style')
    <style>
        @media (max-width: 750px) {

            body {
                background-color: black;
                background-image: linear-gradient(rgb(0 0 0), rgba(0, 0, 0, 0.5)),
                url(https://i.ibb.co.com/TNzcj1N/wp11765696.jpg);
                background-repeat: no-repeat;
                background-position: center;
                width: 100%;
                background-size: cover;
            }

            .account-content {
                padding-bottom: 100px;
            }

            .account-content__top {
                margin-top: 20px !important;
            }
        }

    </style>
@endpush

