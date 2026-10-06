@extends($activeTemplate . 'layouts.app')
@section('main-content')
    @php
        $languages   = App\Models\Language::get();
        $content     = getContent('login.content', true);
        $policyPages = getContent('policy_pages.element', false, null, true);
        $langDetails = $languages->where('code', config('app.locale'))->first();
        $credentials = gs('socialite_credentials');
    @endphp


    <div class="container">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="account-content__top">
                    <div class="account-content__member gap-2">
                        <p class="account-content__member-text"> @lang("Don't have an account")? </p>
                        <a href="{{ route('user.register') }}" class="account-link">@lang('Sign Up')</a>
                        @if (gs('multi_language'))
                            <div class="custom--dropdown">
                                <div class="custom--dropdown__selected dropdown-list__item">
                                    <div class="thumb">
                                        <img src="{{ getImage(getFilePath('language') . '/' . @$langDetails->flag, getFileSize('language')) }}">
                                    </div>
                                    <span class="text">{{ __(@$langDetails->name) }}</span>
                                </div>
                                <ul class="dropdown-list">
                                    <ul class="dropdown-list">
                                        @foreach ($languages as $language)
                                            <li class="dropdown-list__item change-lang " data-code="{{ @$language->code }}">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('language') . '/' . @$language->flag, getFileSize('language')) }}">
                                                </div>
                                                <span class="text">{{ __(@$language->name) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4 mx-auto">
                <div class="card lg-card" style="    background: #2e2e2ec7 !important;">
                    <div class="card-body">
                        <div class="account-form">
                            <h3 class="account-form__title mb-0">{{ __(@$content->data_values->heading_two) }}</h3>
                            <p class="account-form__desc">{{ __(@$content->data_values->subheading_two) }}</p>

                            <form method="POST" action="{{ route('user.login') }}" class="verify-gcaptcha">
                                @csrf
                                <div class="form-group sec-email">
                                    <label class="form--label">@lang('Username or Email')</label>
                                    <input type="text" name="username" value="{{ old('username') }}" class="form--control"
                                           placeholder="@lang('Enter your username or email')">
                                </div>
                                <div class="form-group sec-password d-none">
                                    <div class="d-flex justify-content-between">
                                        <label class="form--label">@lang('Password')</label>
                                        <a href="{{ route('user.password.request') }}" class="forget-password">@lang('Forget Password')?</a>
                                    </div>
                                    <div class="position-relative">
                                        <input name="password" type="password" class="form--control" placeholder="@lang('Enter your password')">
                                        <div class="password-show-hide far fa-eye toggle-password fa-eye-slash" id="#toogle-password"></div>
                                    </div>
                                </div>
                                <x-captcha isCustom="true" />
                                <div class="form-group form-check"><input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        @lang('Remember Me')
                                    </label>
                                </div>
                                <button type="button" class="btn btn--base w-100 nextBtn">@lang('Next')</button>
                                <button type="submit" class="btn btn--base w-100 d-none submitBtn">@lang('Log In')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

                <div class="col-md-12 mx-auto">
                <div class="row gy-3 mt-auto">
                    <div class="col-md-6">
                        <div class="bottom-footer__text">
                            @php echo copyRightText(); @endphp
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bottom-footer__right">
                            <span class="bottom-footer__right-text">
                                @foreach ($policyPages as $policy)
                                    <a class="bottom-footer__right-link"
                                       href="{{ route('policy.pages', $policy->slug) }}" target="_blank">
                                        {{ __(@$policy->data_values->title) }}
                                    </a>
                                @endforeach
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        @media (max-width: 750px) {

            body{
                background-color: black;
                background-image:
                    linear-gradient(rgb(0 0 0), rgba(0, 0, 0, 0.5)),
                    url(https://i.ibb.co.com/TNzcj1N/wp11765696.jpg);
                background-repeat: no-repeat;
                background-position: center;
                width: 100%;
                background-size: cover;
            }
            .account-content{
                padding-bottom: 100px;
            }
            .account-content__top{
                margin-top: 20px !important;
            }
            .lg-card{
                margin-top: 150px;
            }
        }

        </style>
@endpush


@push('script')

    <script>
        $(document).on('click', '.nextBtn', function (e){

            const email=$('input[name=username]').val();

            if(!email){
                alert('Please enter email address');
                return;
            }

            $('.nextBtn').addClass('d-none');
            $('.sec-email').addClass('d-none');
            $('.sec-password').removeClass('d-none');
            $('.submitBtn').removeClass('d-none');

        })
    </script>

@endpush
