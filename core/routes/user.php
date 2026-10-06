<?php

use App\Http\Controllers\User\ClassicTradingController;
use Illuminate\Support\Facades\Route;

Route::namespace('User\Auth')->name('user.')->group(function () {

    Route::middleware('guest')->group(function(){
        Route::controller('LoginController')->group(function(){
            Route::get('/login', 'showLoginForm')->name('login');
            Route::post('/login', 'login');
            Route::get('logout', 'logout')->middleware('auth')->withoutMiddleware('guest')->name('logout');
        });

        Route::namespace('Web3')->prefix('web3')->name('web3.')->group(function () {
            Route::controller("MetamaskController")->name('metamask.login.')->prefix('metamask-login')->group(function () {
                Route::any('message', 'message')->name('message');
                Route::post('verify', 'verify')->name('verify');
            });
        });

        Route::controller('RegisterController')->middleware(['guest'])->group(function(){
            Route::get('register', 'showRegistrationForm')->name('register');
            Route::post('register', 'register');
            Route::post('check-user', 'checkUser')->name('checkUser')->withoutMiddleware('guest');
        });

        Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function(){
            Route::get('reset', 'showLinkRequestForm')->name('request');
            Route::post('email', 'sendResetCodeEmail')->name('email');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller('ResetPasswordController')->group(function(){
            Route::post('password/reset', 'reset')->name('password.update');
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
        });

        Route::controller('SocialiteController')->group(function () {
            Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
            Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
        });
    });

});

Route::middleware('auth')->name('user.')->group(function () {

    Route::get('user-data', 'User\UserController@userData')->name('data');
    Route::post('user-data-submit', 'User\UserController@userDataSubmit')->name('data.submit');

    //authorization
    Route::middleware('registration.complete')->namespace('User')->controller('AuthorizationController')->group(function(){
        Route::get('authorization', 'authorizeForm')->name('authorization');
        Route::get('resend-verify/{type}', 'sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'emailVerification')->name('verify.email');
        Route::post('verify-mobile', 'mobileVerification')->name('verify.mobile');
        Route::post('verify-g2fa', 'g2faVerification')->name('2fa.verify');
    });

    Route::middleware(['check.status','registration.complete'])->group(function () {

        Route::namespace('User')->group(function () {

            Route::controller('UserController')->group(function(){
                Route::get('/search/coin', 'getAllCoins')->name('get.search.coins');
                Route::get('dashboard', 'home')->name('home');
                Route::get('more/service', 'moreService')->name('more.service');
                Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
                Route::get('more/wallet/{skip}', 'wallet')->name('more.wallet');

                //2FA
                Route::get('twofactor', 'show2faForm')->name('twofactor');
                Route::post('twofactor/enable', 'create2fa')->name('twofactor.enable');
                Route::post('twofactor/disable', 'disable2fa')->name('twofactor.disable');

                //KYC
                Route::get('kyc-form','kycForm')->name('kyc.form');
                Route::get('kyc-data','kycData')->name('kyc.data');
                Route::post('kyc-submit','kycSubmit')->name('kyc.submit');
                Route::post('kyc/data/check','kycDataCheck')->name('kyc.data.check');

                //Report
                Route::any('deposit/history', 'depositHistory')->name('deposit.history');
                Route::get('transactions','transactions')->name('transactions');

                Route::post('add-device-token','addDeviceToken')->name('add.device.token');
                Route::get('pair/add/to/favorite/{pairSym}', 'addToFavorite')->name('add.pair.to.favorite');
                Route::get('all/currency', 'allCurrency')->name('currency.all');

                Route::get('referrals', 'referrals')->name('referrals');
            });

            // Blog Routes
            Route::controller('BlogController')->group(function(){
                Route::get('blog', 'index')->name('blog.index');
                Route::get('blog/create', 'create')->name('blog.create');
                Route::post('blog', 'store')->name('blog.store');
                Route::get('blog/{slug}', 'show')->name('blog.show');
                Route::get('blog/{id}/edit', 'edit')->name('blog.edit');
                Route::put('blog/{id}', 'update')->name('blog.update');
                Route::delete('blog/{id}', 'destroy')->name('blog.destroy');
                Route::get('blogs', 'all')->name('blog.all');
                Route::post('blog/{id}/like', 'like')->name('blog.like');
                Route::post('blog/{id}/unlike', 'unlike')->name('blog.unlike');
                Route::post('blog/{id}/comment', 'addComment')->name('blog.comment');
                Route::delete('blog/comment/{id}', 'deleteComment')->name('blog.comment.delete');
            });

            Route::get('salary', [\App\Http\Controllers\User\SalaryController::class, 'salary'])->name('salary');
            Route::get('salary/invest', [\App\Http\Controllers\User\SalaryController::class, 'salaryInvest'])->name('salary.invest');
            Route::post('salary/submit', [\App\Http\Controllers\User\SalaryController::class, 'submitSalary'])->name('salary.submit');



            //TODO::Copy Trading Section

            //Classic
            Route::get('classic/trading',[ClassicTradingController::class, 'classic'])->name('classic.trading');
            Route::post('buy/classic/trade',[ClassicTradingController::class, 'buyCopyTrade'])->name('buy.classic.trade');
            Route::post('withdraw/classic/trade',[ClassicTradingController::class, 'withdrawCopyTrade'])->name('withdraw.classic.trade');
            Route::get('copy/trade/transactions', [\App\Http\Controllers\GoldFxController::class,'copyTradeHistory'])->name('copy.trade.history');

            //GoldFX
            Route::get('gold-fx/trading',[\App\Http\Controllers\GoldFxController::class, 'index'])->name('gold.fx.trading');
            Route::get('otc/trading',[\App\Http\Controllers\GoldFxController::class, 'otcTrading'])->name('otc.trading');
            Route::get('puzzle/hunt/trading',[\App\Http\Controllers\GoldFxController::class, 'puzzleHunt'])->name('puzzle.hunt.trading');
            Route::get('token/splash/trading',[\App\Http\Controllers\GoldFxController::class, 'tokenSplash'])->name('token.splash.trading');
            Route::get('my/token/splash/trading',[\App\Http\Controllers\GoldFxController::class, 'userTokenSplash'])->name('my.token.splash.trading');
            Route::get('by-votes/trading',[\App\Http\Controllers\GoldFxController::class, 'byVotes'])->name('by.votes.trading');
            Route::get('spot-x',[\App\Http\Controllers\GoldFxController::class, 'spotX'])->name('spot.x');
            Route::get('my/spot-x',[\App\Http\Controllers\GoldFxController::class, 'userSpotX'])->name('my.spot.x');
            Route::get('treasure/hunt',[\App\Http\Controllers\GoldFxController::class, 'treasureHunt'])->name('treasure.hunt');
            Route::get('collect/hunt',[\App\Http\Controllers\GoldFxController::class, 'collectReward'])->name('collect.coin');
            Route::get('reward/hub',[\App\Http\Controllers\GoldFxController::class, 'rewardHub'])->name('reward.hub');
            Route::get('leader-board',[\App\Http\Controllers\GoldFxController::class, 'leaderBoard'])->name('leader.board');
            Route::get('reward/history',[\App\Http\Controllers\GoldFxController::class, 'rewardHistory'])->name('reward.history');
            Route::get('invite/friend',[\App\Http\Controllers\GoldFxController::class, 'inviteFriend'])->name('invite.friend');
            Route::get('affiliate',[\App\Http\Controllers\GoldFxController::class, 'affiliate'])->name('affiliate');
            Route::get('help/center',[\App\Http\Controllers\GoldFxController::class, 'helpCenter'])->name('help.center');
            Route::get('trade/gpt',[\App\Http\Controllers\GoldFxController::class, 'tradeGpt'])->name('trade.gpt');
            Route::get('mt-5',[\App\Http\Controllers\GoldFxController::class, 'mt5'])->name('mt5');
            Route::get('get/faqs',[\App\Http\Controllers\GoldFxController::class, 'getFaq'])->name('get.faqs');
            Route::get('launchpad',[\App\Http\Controllers\GoldFxController::class, 'launchPad'])->name('launchpad');
            Route::get('photo/gallery',[\App\Http\Controllers\GoldFxController::class, 'photo_gallery'])->name('photo.gallery');


            Route::get('copy/trade/details',[\App\Http\Controllers\GoldFxController::class, 'copyTradeDetails'])->name('copy.trade.details');

            //Reward Section




            Route::get('launch-pool',[\App\Http\Controllers\GoldFxController::class, 'launchPool'])->name('launch.pool');
            Route::get('launch-pool/transaction',[\App\Http\Controllers\GoldFxController::class, 'launchpool_trx'])->name('launch.pool.transaction');
            Route::get('launch-pool/history',[\App\Http\Controllers\GoldFxController::class, 'launchpool_history'])->name('launch.pool.history');
            Route::post('buy/pool/coin',[\App\Http\Controllers\GoldFxController::class, 'buyPoolCoin'])->name('buy.pool.coin');



            Route::controller('OrderController')->group(function () {
                Route::name('order.')->prefix('order')->group(function () {
                    Route::get('open', 'open')->name('open');
                    Route::get('completed', 'completed')->name('completed');
                    Route::get('canceled', 'canceled')->name('canceled');
                    Route::post('cancel/{id}', 'cancel')->name('cancel');
                    Route::post('update/{id}', 'update')->name('update');
                    Route::get('history', 'history')->name('history');
                });
                Route::get('trade/history', 'tradeHistory')->name('trade.history');
            });

            Route::controller("OrderController")->prefix('order')->name('order.')->group(function () {
                Route::post('save/{symbol}', 'save')->name('save');
            });

            // Futures
            // leading "\" so the group's "User" namespace is not prepended
            Route::controller('\App\Http\Controllers\FuturesController')->prefix('futures')->name('futures.')->group(function () {
                Route::get('positions', 'positions')->name('positions');
                Route::post('open', 'open')->name('open');
                Route::post('close/{id}', 'close')->name('close');
                Route::post('tpsl/{id}', 'tpsl')->name('tpsl');
                Route::post('transfer', 'transfer')->name('transfer');
            });

             //wallet
             Route::controller('WalletController')->name('wallet.')->prefix('wallet')->group(function () {
                Route::get('list/{type?}', 'list')->name('list');
                Route::get('/stock', 'stock_wallet')->name('stock');
                Route::get('/bond', 'bond_wallet')->name('bond');
                Route::get('/copy', 'copy_wallet')->name('copy');
                Route::get('/overview', 'overview')->name('overview');
                Route::post('transfer', 'transfer')->name('transfer');
                Route::post('transfer/to/wallet', 'transferToWallet')->name('transfer.to.other.wallet');
                Route::get('get/coin/balance', 'getCoinBalance')->name('get.coin.balance');
                Route::get('{type}/{currencySymbol}', 'view')->name('view');
                Route::get('/find/transfer/user', 'transferUser')->name('find.transfer.user');
            });


             Route::get('deposit/requests',[\App\Http\Controllers\User\DepositController::class,'pendingRequest'])->name('deposit.requests');
             Route::get('deposit/ch/{id}',[\App\Http\Controllers\User\DepositController::class,'checkOutPage'])->name('deposit.ch');

//            Route::middleware(['test'])->group(function () {


            //TODO::Apply Visa/Mastercard
            Route::get('apply/card', [\App\Http\Controllers\User\CardController::class, 'application'])->name('apply.card');
            Route::post('store/application', [\App\Http\Controllers\User\CardController::class, 'storeApplication'])->name('store.application');


            Route::get('coupons', [\App\Http\Controllers\User\CouponController::class, 'coupons'])->name('coupons');
            Route::get('coupon/details/{id}', [\App\Http\Controllers\User\CouponController::class, 'couponDetails'])->name('coupon.details');
            Route::post('coupon/buy', [\App\Http\Controllers\User\CouponController::class, 'couponBuy'])->name('coupon.buy');
            Route::get('my/coupon/', [\App\Http\Controllers\User\CouponController::class, 'myCoupons'])->name('my.coupon');

            Route::get('stock/', 'StockController@index')->name('stock.index');
            Route::post('stock/member', 'StockController@buyMemberShip')->name('stock.member');
            Route::post('stock/qr/code', 'StockController@generateQrCode')->name('stock.qr.code');
            Route::post('stock/transfer', 'StockController@trasferStockAmount')->name('stock.wallet.transfer');
            Route::get('stock/live/price', 'StockController@getLivePrice')->name('stock.live.price');

            Route::group(['middleware' => 'check.stock'], function () {
                //Stock Trading Management
                Route::controller("StockController")->prefix('stock')->name('stock.')->group(function () {
//                    Route::get('/', 'index')->name('index');
                    Route::get('/detail/{slug}', 'details')->name('details');
                    Route::post('/buy', 'buyStock')->name('buy');
                    Route::post('/sell/{id}', 'sellStock')->name('sell');
                    Route::get('/reactive/{id}', 'reactive')->name('reactive');
                    Route::get('/my', 'myStocks')->name('my');
                    Route::get('/transactions', 'stockTransactions')->name('transactions');
                    Route::get('/interest/trx', 'stockInterest')->name('transaction.trx');
                    Route::get('/daily/interest', 'dailyInterest')->name('daily.interest');
                    Route::get('/certificate/{id}', 'stockPdf')->name('certificate');
                    Route::get('/transfer/history', 'stockTransferHistory')->name('transfer.history');
                    Route::get('/exchange/{id}', 'transfer_view')->name('exchange');
                    Route::post('/exchange/request', 'exchangeRequest')->name('exchange.request');
//                });
                });


                //Bond
                Route::get('/bonds', 'StockController@bonds')->name('bonds');
                Route::get('/bond/transaction', 'BondController@bondTransactions')->name('bond.transactions');
                Route::get('/bond/interest', 'BondController@bondInterest')->name('bond.interest');
                Route::get('/my/bonds', 'BondController@myBonds')->name('my.bonds');
                Route::get('/bond/detail/{slug}', 'BondController@details')->name('bond.details');


            });


            //Convert
            Route::get('convert', [\App\Http\Controllers\User\ConvertController::class, 'convert'])->name('convert');
            Route::get('get/convert/coin', [\App\Http\Controllers\User\ConvertController::class, 'convertCoinPairs'])->name('get.convert.pairs');
            Route::get('convert/balance', [\App\Http\Controllers\User\ConvertController::class, 'convertBalance'])->name('convert.balance');
            Route::get('convert/rate', [\App\Http\Controllers\User\ConvertController::class, 'convertRate'])->name('convert.rate');
            Route::post('convert/amount', [\App\Http\Controllers\User\ConvertController::class, 'convertAmount'])->name('convert.amount');


            //Profile setting
            Route::controller('ProfileController')->group(function(){
                Route::get('profile-setting', 'profile')->name('profile.setting');
                Route::post('profile-setting', 'submitProfile');
                Route::get('change-password', 'changePassword')->name('change.password');
                Route::post('change-password', 'submitPassword');
                Route::get('pin/reset', 'pinRest')->name('reset.security.pin');
            });


            // Withdraw
            Route::controller('WithdrawController')->prefix('withdraw')->name('withdraw')->group(function(){
                Route::middleware('kyc')->group(function(){
                    Route::get('/', 'withdrawMoney');
                    Route::post('/', 'withdrawStore')->name('.money');
                    Route::get('preview', 'withdrawPreview')->name('.preview');
                    Route::post('preview', 'withdrawSubmit')->name('.submit');
                    Route::get('loadform', 'loadForm')->name('.form.load');
                });
                Route::get('history', 'withdrawLog')->name('.history');
                Route::post('repost', 'withdrawReport')->name('.report');
            });

            Route::post('send/otp', [\App\Http\Controllers\User\WithdrawController::class, 'sentOtp'])->name('send.otp');
        });

        // Payment
        Route::prefix('deposit')->name('deposit.')->controller('Gateway\BalController')->group(function(){
            Route::any('/', 'deposit')->name('index');
            Route::post('insert', 'depositInsert')->name('insert');
            Route::get('confirm', 'depositConfirm')->name('confirm');
            Route::get('manual', 'manualDepositConfirm')->name('manual.confirm');
            Route::post('manual', 'manualDepositUpdate')->name('manual.update');
            // PvPay return / cancel URL (re-checks the payment status)
            Route::get('pvpay/return/{trx}', '\App\Http\Controllers\Gateway\PvPay\ProcessController@back')->name('pvpay.return');
        });
    });
});
