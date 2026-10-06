@extends($activeTemplate . 'layouts.app')
@section('main-content')
    @if (gs('registration'))

        @php
            $languages = App\Models\Language::get();
            $content = getContent('register.content', true);
            $langDetails = $languages->where('code', config('app.locale'))->first();
            $credentials = gs('socialite_credentials');
        @endphp


        <div class="container">
            <div class="row">
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

                <div class="col-md-6 mx-auto">
                    <div class="account-content">
                        <div class="row">
                            <div class="col-md-8 mx-auto">
                                <div class="card" style="    background: #2e2e2ec7 !important;">
                                    <div class="card-body">
                                        <div class="account-form">
                                            <h3 class="account-form__title mb-0"> {{ __(@$content->data_values->heading_two) }}</h3>
                                            <p class="account-form__desc">{{ __(@$content->data_values->subheading_two) }}</p>


                                            <form action="{{ route('user.register') }}" method="POST"
                                                  class="verify-gcaptcha">
                                                @csrf
                                                <div class="row">
                                                    @if(request()->get('reference'))
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="referenceBy"
                                                                       class="form--label">@lang('Reference by')</label>
                                                                <input type="text" name="referBy" id="referenceBy"
                                                                       class="form--control"
                                                                       value="{{ request()->get('reference') }}"
                                                                       readonly>
                                                            </div>
                                                        </div>

                                                    @elseif (session()->get('reference') != null)
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="referenceBy"
                                                                       class="form--label">@lang('Reference by')</label>
                                                                <input type="text" name="referBy" id="referenceBy"
                                                                       class="form--control"
                                                                       value="{{ session()->get('reference') }}"
                                                                       readonly>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form--label">@lang('First Name')</label>
                                                            <input type="text" class="form--control" name="firstname"
                                                                   value="{{ old('firstname') }}" required
                                                                   placeholder="@lang('Your First Name')">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form--label">@lang('Last Name')</label>
                                                            <input type="text" class="form--control" name="lastname"
                                                                   value="{{ old('lastname') }}" required
                                                                   placeholder="@lang('Your Last Name')">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form--label">@lang('E-Mail Address')</label>
                                                            <input type="email" class="form--control checkUser"
                                                                   placeholder="@lang('Your email')" name="email"
                                                                   value="{{ old('email') }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form--label">@lang('Password')</label>
                                                            <input type="password"
                                                                   class="form--control @if (gs('secure_password')) secure-password @endif"
                                                                   name="password" placeholder="@lang('Your password')"
                                                                   required autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form--label">@lang('Confirm Password')</label>
                                                            <input type="password" class="form--control"
                                                                   name="password_confirmation"
                                                                   placeholder="@lang('Password Confirmation')"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <x-captcha isCustom="true"/>
                                                </div>
                                                @if (gs('agree'))
                                                    @php
                                                        $policyPages = getContent('policy_pages.element', false, null, true);
                                                    @endphp
                                                    <div class="form-group">
                                                        <input type="checkbox" id="agree"
                                                               @checked(old('agree')) name="agree" required>
                                                        <label for="agree">@lang('I agree with')</label>
                                                        <span>
                                                @foreach ($policyPages as $policy)
                                                                <a class="text--base"
                                                                   href="{{ route('policy.pages', $policy->slug) }}"
                                                                   target="_blank">{{ __($policy->data_values->title) }}</a>
                                                                @if (!$loop->last)
                                                                    ,
                                                                @endif
                                                            @endforeach
                                            </span>
                                                    </div>
                                                @endif
                                                <button type="submit" id="recaptcha"
                                                        class="btn btn--base w-100"> @lang('Register')</button>
                                            </form>
                                        </div>
                                    </div>
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
        </div>

        <div class="modal fade custom--modal" id="existModalCenter" tabindex="-1" role="dialog"
             aria-labelledby="existModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                        <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </span>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark btn--sm"
                                data-bs-dismiss="modal">@lang('Close')</button>
                        <a href="{{ route('user.login') }}" class="btn btn--base btn--sm">@lang('Login')</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        @include($activeTemplate . 'partials.registration_disabled')
    @endif
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
            .account-content__top{
                margin-top: 20px !important;
            }

        }

        .country-code .input-group-text {
            background: #fff !important;
        }

        .country-code select {
            border: none;
        }

        .country-code select:focus {
            border: none;
            outline: none;
        }

    </style>
@endpush

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('script')
    <script>
        "use strict";
        (function ($) {

            $('.checkUser').on('focusout', function (e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                var data = {
                    email: value,
                    _token: token
                }

                $.post(url, data, function (response) {
                    if (response.data != false) {
                        $('#existModalCenter').modal('show');
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
