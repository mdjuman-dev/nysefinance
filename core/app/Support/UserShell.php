<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Route;

/**
 * Data the Vue user layout needs on every page: profile summary and the
 * navigation that mirrors partials/user_sidebar.blade.php.
 */
class UserShell
{
    public static function data(User $user): array
    {
        return [
            'user' => [
                'fullname' => $user->fullname,
                'username' => $user->username,
                'email'    => $user->email,
                'uid'      => $user->uid,
                // null lets the UI fall back to initials (the placeholder route redirects to login)
                'avatar'   => $user->image && is_file(getFilePath('userProfile') . '/' . $user->image)
                    ? asset(getFilePath('userProfile') . '/' . $user->image)
                    : null,
                'kyc'      => match ((string) $user->kv) { '1' => 'verified', '2' => 'pending', default => 'unverified' },
                'isAgent'  => checkAgent(),
            ],
            'referralLink' => route('home') . '?reference=' . $user->username,
            // Same condition and image as the #chmsModal popup in layouts/master.blade.php
            'notice' => gs('notice_status') == 'enable' && gs('notice_file')
                ? 'https://nysefinance.com/assets/images/currency/wh-notice.jpeg'
                : null,
            'links' => self::links([
                'home'         => 'user.home',
                'deposit'      => ['user.wallet.overview', ['sc' => 'tr']],
                'withdraw'     => 'user.withdraw',
                'wallet'       => 'user.wallet.overview',
                'trade'        => 'trade',
                'futures'      => 'futures',
                'stocks'       => 'user.stock.index',
                'bonds'        => 'user.bonds',
                'orders'       => 'user.order.open',
                'transactions' => 'user.transactions',
                'referrals'    => 'user.referrals',
                'support'      => 'ticket.index',
                'p2p'          => checkAgent() ? 'user.p2p.dashboard' : 'p2p',
                'more'         => 'user.more.service',
                'help'         => 'user.help.center',
                'profile'      => 'user.profile.setting',
                'password'     => 'user.change.password',
                'security'     => 'user.twofactor',
                'kyc'          => 'user.kyc.form',
                'logout'       => 'user.logout',
            ]),
            'nav' => self::nav($user),
        ];
    }

    private static function nav(User $user): array
    {
        $groups = [
            ['label' => 'Overview', 'items' => [
                ['Dashboard', 'ri-dashboard-3-line', 'user.home'],
                ['Manage Wallet', 'ri-wallet-3-line', 'user.wallet.overview', 'user.wallet.*'],
                ['Manage Order', 'ri-file-list-3-line', 'user.order.open', 'user.order.*'],
            ]],
            ['label' => 'Trade', 'items' => [
                ['Spot Trade', 'ri-exchange-line', 'trade'],
                ['Futures', 'ri-line-chart-line', 'futures'],
                ['Classic Trade', 'ri-copper-coin-line', 'user.classic.trading'],
                ['Gold-FX', 'ri-coins-line', 'user.gold.fx.trading'],
                ['OTC Trade', 'ri-hand-coin-line', 'user.otc.trading'],
                ['Trade History', 'ri-history-line', 'user.trade.history'],
            ]],
            ['label' => 'Stocks', 'items' => [
                ['Stock Market', 'ri-stock-line', 'user.stock.index'],
                ['My Stock', 'ri-briefcase-4-line', 'user.stock.my'],
                ['Stock History', 'ri-file-history-line', 'user.stock.transactions'],
                ['Bonds', 'ri-bank-line', 'user.bonds', 'user.bond*'],
                ['My Bonds', 'ri-safe-2-line', 'user.my.bonds'],
            ]],
            ['label' => 'P2P', 'items' => array_values(array_filter([
                ['P2P Market', 'ri-team-line', 'p2p'],
                checkAgent() ? ['P2P Center', 'ri-store-2-line', 'user.p2p.dashboard'] : null,
            ]))],
            ['label' => 'Finance', 'items' => [
                ['Deposit History', 'ri-download-2-line', 'user.deposit.history'],
                ['Deposit Requests', 'ri-inbox-archive-line', 'user.deposit.requests'],
                ['Withdraw History', 'ri-upload-2-line', 'user.withdraw.history'],
                ['Transactions', 'ri-arrow-left-right-line', 'user.transactions'],
                ['Apply For Card', 'ri-bank-card-line', 'user.apply.card'],
            ]],
            ['label' => 'Earn', 'items' => [
                ['My Affiliation', 'ri-share-forward-line', 'user.referrals'],
                ['Salary', 'ri-money-dollar-circle-line', 'user.salary'],
                ['Token Splash', 'ri-drop-line', 'user.token.splash.trading'],
                ['Puzzle Hunt', 'ri-puzzle-line', 'user.puzzle.hunt.trading'],
                ['By Votes', 'ri-thumb-up-line', 'user.by.votes.trading'],
                ['JackPlay', 'ri-gift-line', 'user.coupons'],
            ]],
            ['label' => 'Community', 'items' => [
                // 'My Blog' (user.blog.index) is hidden: BlogController aborts 404 there.
                ['Community Posts', 'ri-article-line', 'user.blog.all'],
                ['Get Support', 'ri-customer-service-2-line', 'ticket.index', 'ticket.*'],
            ]],
            ['label' => 'Account', 'items' => array_values(array_filter([
                ['Profile', 'ri-user-3-line', 'user.profile.setting'],
                ['Change Password', 'ri-lock-password-line', 'user.change.password'],
                ['Security', 'ri-shield-keyhole-line', 'user.twofactor'],
                (string) $user->kv === '0' ? ['KYC Form', 'ri-id-card-line', 'user.kyc.form'] : null,
            ]))],
        ];

        return collect($groups)->map(fn ($g) => [
            'label' => $g['label'],
            'items' => collect($g['items'])
                ->filter(fn ($i) => Route::has($i[2]))
                ->map(fn ($i) => ['label' => $i[0], 'icon' => $i[1], 'href' => route($i[2]), 'active' => request()->routeIs($i[3] ?? $i[2])])
                ->values(),
        ])->filter(fn ($g) => $g['items']->isNotEmpty())->values()->all();
    }

    private static function links(array $map): array
    {
        return collect($map)->map(function ($def) {
            [$name, $params] = is_array($def) ? $def : [$def, []];
            return Route::has($name) ? route($name, $params) : null;
        })->all();
    }
}
