<?php

namespace App\Support;

use Illuminate\Support\Facades\Blade;

/**
 * Props shared by the Vue auth pages (login, register, password reset,
 * authorization). The forms post natively to the existing routes, so
 * validation errors come back through the session; old input is passed here.
 */
class AuthPage
{
    public static function props(array $extra = []): array
    {
        $captcha = trim(Blade::render('<x-captcha isCustom="true" />'));

        return array_merge([
            'old'      => (object) collect(session()->getOldInput())->except(['password', 'password_confirmation', '_token'])->all(),
            'captcha'  => $captcha !== '' ? $captcha : null,
            'policies' => getContent('policy_pages.element', false, null, true)
                ->map(fn ($p) => ['title' => __($p->data_values->title ?? ''), 'url' => route('policy.pages', $p->slug)])->values(),
            'authUrls' => [
                'login'      => route('user.login'),
                'register'   => route('user.register'),
                'forgot'     => route('user.password.request'),
                'checkUser'  => route('user.checkUser'),
                'home'       => route('home'),
            ],
        ], $extra);
    }

    /** CMS heading text, with the "{{ }}" highlight markers the Blade theme used stripped. */
    public static function text($value): string
    {
        return trim(str_replace(['{{', '}}'], '', __((string) $value)));
    }
}
