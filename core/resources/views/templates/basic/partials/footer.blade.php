@php
    $footer      = getContent('footer.content', true);
    $socialIcons = getContent('social_icon.element', orderById: true);
    $policyPages = getContent('policy_pages.element');
@endphp

<footer class="footer-area">
    <div class="py-60">
        <div class="container">
            <div class="row gy-4 justify-content-center">
                <div class="col-sm-6 col-xl-6">
                    <div class="footer-item">
                        <div class="footer-item__logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ siteLogo() }}">
                            </a>
                        </div>
                        <p class="footer-item__desc">{{ __(@$footer->data_values->about_info) }}</p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-2">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('Quick Links')</h5>
                        <ul class="footer-menu">

                            @php
                            $expect_name=['HOME','Market','Crypto Currency', 'p2p'];
                                $pages=\App\Models\Page::orderByDesc('created_at')->whereNotIn('name', $expect_name)->get();
                            @endphp

                            @foreach($pages as $page)
                                <li class="footer-menu__item">
                                    <a href="/{{ $page->slug }}" class="footer-menu__link"> {{$page->name}}</a>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="bottom-footer">
        <div class="container">
            <div class="bottom-footer__style py-3">
                <div class="gap-4 d-flex flex-wrap justify-content-between align-items-center">
                    <div class="bottom-footer__text">
                        @php echo copyRightText(); @endphp
                    </div>
                    <div class="footer-list-wrapper">
                        <ul class="social-list">
                            @foreach ($socialIcons as $sIcon)
                                <li class="social-list__item">
                                    <a href="{{ @$sIcon->data_values->url }}" target="_blank" class="social-list__link">
                                        @php echo @$sIcon->data_values->icon; @endphp
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
