<?php

use App\Http\Controllers\ReportAutoResolveController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\StatementController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\StockCronController;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('en', [\App\Http\Controllers\SiteController::class, 'indexEn'])->name('home.en');
Route::get('jp', [\App\Http\Controllers\SiteController::class, 'indexJjp'])->name('home.jp');


Route::get('cron', 'CronController@cron')->name('cron');
Route::get('/trade/cron/buy', [\App\Http\Controllers\CronOrderController::class, 'tradeBuy']);
Route::get('/trade/cron/sell', [\App\Http\Controllers\CronOrderController::class, 'tradeSell']);





//ERC Latest Transaction
Route::get('erc/latest/transactions',[\App\Http\Controllers\SiteController::class,'ercLatestTransaction'])->name('erc.latest.transaction');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('new/chat/{id}', 'fetchNewChat')->name('new.chat');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

Route::get('app/deposit/confirm/{hash}', 'Gateway\PaymentController@appDepositConfirm')->name('deposit.app.confirm');


Route::controller("TradeController")->prefix('trade')->group(function () {
    Route::get('/order/book/{symbol}', 'orderBook')->name('trade.order.book');
    Route::get('pairs', 'pairs')->name('trade.pairs');
    Route::get('history/{symbol}', 'history')->name('trade.history');
    Route::get('order/list/{pairSym}', 'orderList')->name('trade.order.list');
    Route::get('/{symbol?}', 'trade')->name('trade');
//    Route::get('/b/{symbol?}', 'tradeBox')->name('tradeBox');
});

Route::get('/td/{symbol?}', [\App\Http\Controllers\TradeController::class, 'tradeBox'])->name('tradeBox');




// Futures (USDT-margined). The cron settles liquidations / TP / SL; call it every minute.
Route::get('futures/cron', [\App\Http\Controllers\FuturesController::class, 'cron'])->name('futures.cron');
Route::get('/futures/{symbol?}', [\App\Http\Controllers\FuturesController::class, 'index'])->name('futures');


Route::namespace('P2P')->group(function () {
    Route::controller("HomeController")->prefix('p2p')->group(function () {
        Route::get("/advertiser/{username}", 'advertiser')->name('p2p.advertiser');
        Route::get("/{type?}/{coin?}/{currency?}/{paymentMethod?}/{region?}/{amount?}", 'p2p')->name('p2p');
    });
});

Route::controller('SiteController')->group(function () {
    Route::get('/pwa/configuration', 'pwaConfiguration')->name('pwa.configuration');
    Route::get('/market/list', 'marketList')->name('market.list');
    Route::get('/crypto/list', 'cryptoCurrencyList')->name('crypto_currency.list');
    Route::get('/market', 'market')->name('market');
    Route::post('/subscribe', 'subscribe')->name('subscribe');
    Route::get('/crypto-currency', 'crypto')->name('crypto_currencies');
    Route::get('/crypto/currency/{symbol}', 'cryptoCurrencyDetails')->name('crypto.details');
    Route::get('/about-us', 'about')->name('about');
    Route::post('pusher/auth/{socketId}/{channelName}', "pusherAuthentication");

    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');
    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');
    Route::get('blog/{slug}', 'blogDetails')->name('blog.details');
    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');
    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image')->withoutMiddleware('maintenance');
    Route::get('maintenance-mode','maintenance')->withoutMiddleware('maintenance')->name('maintenance');

    Route::get('/{slug}', 'pages')->name('pages');
//    Route::get('/enc', 'indexEn')->name('home.en');
    Route::get('/', 'index')->name('home');
//    Route::get('/jp', 'indexJjp')->name('home.jp');
    Route::get('/download/apk', 'downloadApk')->name('download.apk');
});


//Binance Trx Fetch
//TODO::Add this on CRON each minute
Route::get('fetch/trx', [\App\Http\Controllers\CheckTransaction::class,'check'])->name('fetch.trx');
Route::get('check/crypto/transaction', [\App\Http\Controllers\CheckTransaction::class,'checkDepositStatus'])->name('check.crypto.transaction');


//Salary
Route::get('payment/sub/cron', [SalaryController::class, 'paymentSubCron']);

Route::get('/process/lavel/commission', function (){
    \Illuminate\Support\Facades\Artisan::call('queue:work');
});

Route::get('certificate/{id}', [\App\Http\Controllers\User\StockController::class, 'public_certificate'])->name('public.certificate');

Route::get('/stock/overview', [\App\Http\Controllers\Admin\ManualStockController::class, 'detailsStock']);
Route::get('/verify/login', [\App\Http\Controllers\Admin\ManualStockController::class, 'verifyLogin']);
Route::post('/v3/sell/request', [\App\Http\Controllers\Admin\ManualStockController::class, 'sellRequest']);

//TODO::Weekly Statement Cron
Route::get('weekly/statement/crn',[StatementController::class, 'sendStatement']);

//Auto Resolve User Report
Route::get('/resolve/user/report', [ReportAutoResolveController::class, 'index']);

Route::get('/stock/interest', 'StockCronController@dailyInterest');
Route::get('copy/daily/interest', [\App\Http\Controllers\CopyCronController::class, 'dailyInterest']);
