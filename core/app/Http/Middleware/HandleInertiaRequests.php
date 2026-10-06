<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    // Built assets live in ../build (see RootVite); a new build forces a full reload.
    public function version(\Illuminate\Http\Request $request): ?string
    {
        $manifest = dirname(base_path()) . '/build/manifest.json';
        return is_file($manifest) ? hash_file('xxh128', $manifest) : null;
    }

    public function share(Request $request): array
    {
        $user = auth()->user();

        return array_merge(parent::share($request), [
            'site' => fn () => [
                'name'     => gs('site_name'),
                'logo'     => siteLogo(),
                'favicon'  => siteFavicon(),
                'mark'     => asset('assets/images/logo_icon/logo_mark.png'),
                'currency' => gs('cur_text'),
                'publicHome' => (bool) config('app.public_home'),
                'apk'      => 'https://apk.nysefinance.com/nysefinance.apk',
            ],
            'auth' => fn () => [
                'user' => $user ? [
                    'fullname' => $user->fullname,
                    'email'    => $user->email,
                ] : null,
            ],
            'routes' => fn () => [
                'home'      => route('home'),
                'login'     => route('user.login'),
                'register'  => route('user.register'),
                'dashboard' => route('user.home'),
                'about'     => route('about'),
                'contact'   => route('contact'),
                'crypto'    => route('crypto_currencies'),
                'cryptoList' => route('crypto_currency.list'),
                'privacy'   => url('/privacy-policy'),
                'terms'     => url('/terms-condition'),
                'trade'     => url('/trade-policy'),
            ],
            // This app has no EncryptCookies middleware, so the X-XSRF-TOKEN header Inertia
            // sends cannot be verified. The client sends X-CSRF-TOKEN from this value instead.
            'csrf' => fn () => csrf_token(),
            'shell' => fn () => $user ? \App\Support\UserShell::data($user) : null,
            // Laravel's withNotify() flashes [['success', 'msg'], ...]
            // ...and some controllers use ['success' => 'msg'] or withErrors(['error' => 'msg']).
            'notify' => function () {
                $items = collect(session('notify', []))->map(fn ($n, $k) => is_array($n)
                    ? ['type' => $n[0] ?? 'info', 'message' => __($n[1] ?? '')]
                    : ['type' => is_string($k) ? $k : 'info', 'message' => __($n)]);
                // e.g. stock wallet transfer: ->with('success', 'Amount Successfully Transferred')
                if (is_string(session('success'))) {
                    $items->push(['type' => 'success', 'message' => __(session('success'))]);
                }
                $errors = session('errors');
                foreach (['error', 'errors'] as $key) {
                    if ($errors && $errors->has($key)) {
                        $items->push(['type' => 'error', 'message' => $errors->first($key)]);
                    }
                }
                return $items->values();
            },
        ]);
    }
}
