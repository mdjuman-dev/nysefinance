<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\AdminNotification;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Product;
use App\Models\StockMember;
use App\Models\StockSellRequest;
use App\Models\StockTransaction;
use App\Models\StockTransfer;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\UserStock;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\P2P\Trade as P2PTrade;
use App\Models\P2P\Ad;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    public function dashboard()
    {

        $pageTitle = 'Dashboard';

        // User Info
        $widget['total_users']             = User::count();
        $widget['verified_users']          = User::active()->count();
        $widget['email_unverified_users']  = User::emailUnverified()->count();
        $widget['mobile_unverified_users'] = User::mobileUnverified()->count();

        $orders                             = Order::query();
        $widget['order_count']['total']     = (clone $orders)->count();
        $widget['order_count']['open']      = (clone $orders)->open()->count();
        $widget['order_count']['completed'] = (clone $orders)->completed()->count();
        $widget['order_count']['canceled']  = (clone $orders)->canceled()->count();

        $orders = (clone $orders)->where('status', '!=', Status::ORDER_CANCELED)->groupBy('pair_id');

        $widget['order']['list']   = (clone $orders)->selectRaw("id,pair_id,SUM(amount) as total_amount")->with('pair:id,symbol,coin_id', 'pair.coin:id,symbol')->orderBy('total_amount', 'desc')->take(6)->get();
        $widget['order']['symbol'] = (clone $orders)->selectRaw("*,count(*) as total")->orderBy('total', 'desc')->with('pair')->get()->pluck('pair.symbol');
        $widget['order']['count']  = (clone $orders)->selectRaw("count(*) as total")->orderBy('total', 'desc')->with('pair')->get()->pluck('total');

        $currency = Currency::query();

        $widget['total_trade'] = Trade::count();

        $widget['total_currency']        = (clone $currency)->count();
        $widget['total_crypto_currency'] = (clone $currency)->crypto()->count();
        $widget['total_fiat_currency']   = (clone $currency)->fiat()->count();

        $deposit                              = Deposit::with('currency')->where('status', '!=', Status::PAYMENT_INITIATE)->where('status', '!=', Status::PAYMENT_REJECT)->groupBy('currency_id');
        $widget['deposit']['list']            = (clone $deposit)->selectRaw('*,SUM(amount) as total_amount')->orderBy('total_amount', 'DESC')->take(6)->get();
        $widget['deposit']['currency_count']  = (clone $deposit)->selectRaw('*,count(*) as count')->orderBy('count', 'DESC')->get()->plucK('count');
        $widget['deposit']['currency_symbol'] = (clone $deposit)->selectRaw('*,count(*) as count')->orderBy('count', 'DESC')->get()->plucK('currency.symbol');

        $withdraw                              = Withdrawal::with('withdrawCurrency')->where('status', '!=', Status::PAYMENT_INITIATE)->where('status', '!=', Status::PAYMENT_REJECT)->groupBy('currency');
        $widget['withdraw']['list']            = (clone $withdraw)->selectRaw('*,SUM(amount) as total_amount')->orderBy('total_amount', 'DESC')->take(6)->get();
        $widget['withdraw']['currency_count']  = (clone $withdraw)->selectRaw('*,count(*) as count')->orderBy('count', 'DESC')->get()->plucK('count');
        $widget['withdraw']['currency_symbol'] = (clone $withdraw)->selectRaw('*,count(*) as count')->orderBy('count', 'DESC')->get()->plucK('withdrawCurrency.symbol');

        $p2pTrade                         = P2PTrade::query();
        $widget['p2p']['total_trade']     = (clone $p2pTrade)->count();
        $widget['p2p']['running_trade']   = (clone $p2pTrade)->running()->count();
        $widget['p2p']['completed_trade'] = (clone $p2pTrade)->completed()->count();
        $widget['p2p']['total_ad']        = Ad::count();

        // user Browsing, Country, Operating Log
        $userLoginData = UserLogin::where('created_at', '>=', Carbon::now()->subDay(30))->get(['browser', 'os', 'country']);

        $chart['user_browser_counter'] = $userLoginData->groupBy('browser')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_os_counter'] = $userLoginData->groupBy('os')->map(function ($item, $key) {
            return collect($item)->count();
        });
        $chart['user_country_counter'] = $userLoginData->groupBy('country')->map(function ($item, $key) {
            return collect($item)->count();
        })->sort()->reverse()->take(5);

        $widget['total_stock']=Product::count();
        $widget['total_stock_buyed']=UserStock::where('status', 'buy')->count();
        $widget['pending_deposit']=Deposit::where('status', '2')->count();
        $widget['pending_kyc']=User::where('kv', '2')->count();
        $widget['pending_sell_request']=StockSellRequest::where('status', 'pending')->count();

        $widget['stock_buy_amount']=UserStock::where('status', 'buy')->sum('invest_amount');
        $widget['stock_sell_amount']=UserStock::where('status', 'sell')->sum('invest_amount');
        // stock_transactions has millions of interest rows. Everything before today can't
        // change, so that part is summed once per day and cached; today's part is a cheap
        // range scan on (type, created_at, amount).
        $widget['daily_interest_amount'] = StockTransaction::where('type', 'interest')
            ->where('created_at', '>=', today())->sum('amount');
        $widget['stock_interest_amount'] = $widget['daily_interest_amount'] + cache()->remember(
            'admin_dash_stock_interest_until_' . today()->toDateString(), today()->endOfDay(),
            fn () => StockTransaction::where('type', 'interest')->where('created_at', '<', today())->sum('amount')
        );

        $widget['initiated'] = Deposit::with(['user', 'gateway', 'currency','wallet.currency'])
            ->join('currencies', 'deposits.currency_id', 'currencies.id')
            ->where('deposits.status', '0')->sum(DB::raw('currencies.rate * deposits.amount'));

        $widget['total_withdraw']=Withdrawal::where('status', '1')->sum('final_amount');
        $widget['stock_transfer']=StockTransfer::sum('amount');

        $widget['p2p_sell_order']=Transaction::where('remark', 'p2p_sell_order')->sum('amount');
        $widget['p2p_buy_order']=Transaction::where('remark', 'p2p_buy_order')->sum('amount');

        // Futures (open book exposure + house result from closed positions)
        $futures = \App\Models\FuturePosition::query();
        $widget['futures'] = [
            'open'        => (clone $futures)->where('status', 'open')->count(),
            'open_margin' => (clone $futures)->where('status', 'open')->sum('margin'),
            'liquidated'  => (clone $futures)->where('status', 'liquidated')->count(),
            'user_pnl'    => (clone $futures)->where('status', '!=', 'open')->sum('realized_pnl'),
            'fees'        => (clone $futures)->sum(DB::raw('open_fee + COALESCE(close_fee, 0)')),
        ];

        $widget['users_today']    = User::whereDate('created_at', today())->count();
        $widget['users_month']    = User::where('created_at', '>=', now()->startOfMonth())->count();
        $widget['total_deposit']  = Deposit::join('currencies', 'deposits.currency_id', 'currencies.id')
            ->where('deposits.status', Status::PAYMENT_SUCCESS)->sum(DB::raw('currencies.rate * deposits.amount'));
        $widget['pending_withdraw'] = Withdrawal::where('status', Status::PAYMENT_PENDING)->count();
        // withdrawals are stored per currency; convert like deposits so the totals compare
        $widget['total_withdraw_base'] = Withdrawal::leftJoin('currencies', 'withdrawals.currency', 'currencies.symbol')
            ->where('withdrawals.status', Status::PAYMENT_SUCCESS)->sum(DB::raw('COALESCE(currencies.rate, 1) * withdrawals.final_amount'));

        // Deposit vs withdraw flow, last 12 months (successful only, in site currency)
        $from      = now()->subMonths(11)->startOfMonth();
        $months    = collect(range(0, 11))->map(fn ($i) => $from->copy()->addMonths($i)->format('Y-m'));
        $depFlow   = Deposit::join('currencies', 'deposits.currency_id', 'currencies.id')
            ->where('deposits.status', Status::PAYMENT_SUCCESS)->where('deposits.created_at', '>=', $from)
            ->selectRaw("DATE_FORMAT(deposits.created_at, '%Y-%m') as ym, SUM(currencies.rate * deposits.amount) as total")
            ->groupBy('ym')->pluck('total', 'ym');
        $wdFlow    = Withdrawal::leftJoin('currencies', 'withdrawals.currency', 'currencies.symbol')
            ->where('withdrawals.status', Status::PAYMENT_SUCCESS)->where('withdrawals.created_at', '>=', $from)
            ->selectRaw("DATE_FORMAT(withdrawals.created_at, '%Y-%m') as ym, SUM(COALESCE(currencies.rate, 1) * withdrawals.amount) as total")
            ->groupBy('ym')->pluck('total', 'ym');
        $chart['flow'] = [
            'labels'   => $months->map(fn ($m) => \Carbon\Carbon::createFromFormat('Y-m', $m)->format('M y'))->values(),
            'deposit'  => $months->map(fn ($m) => round((float) ($depFlow[$m] ?? 0), 2))->values(),
            'withdraw' => $months->map(fn ($m) => round((float) ($wdFlow[$m] ?? 0), 2))->values(),
        ];

        $widget['latest_users'] = User::latest('id')->take(6)->get(['id', 'firstname', 'lastname', 'username', 'email', 'uid', 'created_at', 'kv']);

        return view('admin.dashboard', compact('pageTitle', 'widget', 'chart'));
    }



    public function loadData(Request $request)
    {
        $modelName = $request->model_name;
        $query     = "App\\Models\\$modelName"::query()->skip($request->skip)->take($request->take);

        if ($modelName == 'Order') {
            $query->where('status', '!=', Status::ORDER_CANCELED)
                ->groupBy('pair_id')
                ->selectRaw("id,pair_id,SUM(amount) as total_amount")
                ->with('pair:id,symbol,coin_id', 'pair.coin:id,symbol')
                ->orderBy('total_amount', 'desc');
        }
        if ($modelName == 'Deposit') {
            $query->with('currency')->where('status', '!=', Status::PAYMENT_INITIATE)->where('status', '!=', Status::PAYMENT_REJECT)
                ->groupBy('currency_id')
                ->selectRaw('*,SUM(amount) as total_amount')
                ->orderBy('total_amount', 'DESC');
        }
        if ($modelName == 'Withdrawal') {
            $query->with('withdrawCurrency')->where('status', '!=', Status::PAYMENT_REJECT)
                ->groupBy('currency')
                ->selectRaw('*,SUM(amount) as total_amount')
                ->orderBy('total_amount', 'DESC');
        }

        $data = $query->skip($request->skip)->take($request->take)->get();
        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }


    public function depositAndWithdrawReport(Request $request)
    {

        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y'  : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }
        $deposits = Deposit::successful()
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();


        $withdrawals = Withdrawal::approved()
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'deposits' => getAmount($deposits->where('created_on', $date)->first()?->amount ?? 0),
                'withdrawals' => getAmount($withdrawals->where('created_on', $date)->first()?->amount ?? 0)
            ];
        }

        $data = collect($data);

        // Monthly Deposit & Withdraw Report Graph
        $report['created_on']   = $data->pluck('created_on');
        $report['data']     = [
            [
                'name' => 'Deposited',
                'data' => $data->pluck('deposits')
            ],
            [
                'name' => 'Withdrawn',
                'data' => $data->pluck('withdrawals')
            ]
        ];

        return response()->json($report);
    }

    public function transactionReport(Request $request)
    {

        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y'  : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }

        $plusTransactions   = Transaction::where('trx_type', '+')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $minusTransactions  = Transaction::where('trx_type', '-')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();


        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'credits' => getAmount($plusTransactions->where('created_on', $date)->first()?->amount ?? 0),
                'debits' => getAmount($minusTransactions->where('created_on', $date)->first()?->amount ?? 0)
            ];
        }

        $data = collect($data);

        // Monthly Deposit & Withdraw Report Graph
        $report['created_on']   = $data->pluck('created_on');
        $report['data']     = [
            [
                'name' => 'Plus Transactions',
                'data' => $data->pluck('credits')
            ],
            [
                'name' => 'Minus Transactions',
                'data' => $data->pluck('debits')
            ]
        ];

        return response()->json($report);
    }


    private function getAllDates($startDate, $endDate)
    {
        $dates = [];
        $currentDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('d-F-Y');
            $currentDate->modify('+1 day');
        }

        return $dates;
    }

    private function  getAllMonths($startDate, $endDate)
    {
        if ($endDate > now()) {
            $endDate = now()->format('Y-m-d');
        }

        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        $months = [];

        while ($startDate <= $endDate) {
            $months[] = $startDate->format('F-Y');
            $startDate->modify('+1 month');
        }

        return $months;
    }


    public function profile()
    {
        $pageTitle = 'Profile';
        $admin = auth('admin')->user();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ]);
        $user = auth('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return to_route('admin.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $admin = auth('admin')->user();
        return view('admin.password', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = auth('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password doesn\'t match!!'];
            return back()->withNotify($notify);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return to_route('admin.password')->withNotify($notify);
    }

    public function notifications()
    {
        $notifications = AdminNotification::orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        $hasUnread = AdminNotification::where('is_read', Status::NO)->exists();
        $hasNotification = AdminNotification::exists();
        $pageTitle = 'Notifications';
        return view('admin.notifications', compact('pageTitle', 'notifications', 'hasUnread', 'hasNotification'));
    }


    public function notificationRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->is_read = Status::YES;
        $notification->save();
        $url = $notification->click_url;
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }

    public function requestReport()
    {
        $pageTitle = 'Your Listed Report & Request';
        $arr['app_name'] = systemDetails()['name'];
        $arr['app_url'] = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASECODE');
        $url = "https://license.viserlab.com/issue/get?" . http_build_query($arr);
        $response = CurlRequest::curlContent($url);
        $response = json_decode($response);
        if (!$response || !@$response->status || !@$response->message) {
            return to_route('admin.dashboard')->withErrors('Something went wrong');
        }
        if ($response->status == 'error') {
            return to_route('admin.dashboard')->withErrors($response->message);
        }
        $reports = $response->message[0];
        return view('admin.reports', compact('reports', 'pageTitle'));
    }

    public function reportSubmit(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bug,feature',
            'message' => 'required',
        ]);
        $url = 'https://license.viserlab.com/issue/add';

        $arr['app_name'] = systemDetails()['name'];
        $arr['app_url'] = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASECODE');
        $arr['req_type'] = $request->type;
        $arr['message'] = $request->message;
        $response = CurlRequest::curlPostContent($url, $arr);
        $response = json_decode($response);
        if (!$response || !@$response->status || !@$response->message) {
            return to_route('admin.dashboard')->withErrors('Something went wrong');
        }
        if ($response->status == 'error') {
            return back()->withErrors($response->message);
        }
        $notify[] = ['success', $response->message];
        return back()->withNotify($notify);
    }

    public function readAllNotification()
    {
        AdminNotification::where('is_read', Status::NO)->update([
            'is_read' => Status::YES
        ]);
        $notify[] = ['success', 'Notifications read successfully'];
        return back()->withNotify($notify);
    }

    public function deleteAllNotification()
    {
        AdminNotification::truncate();
        $notify[] = ['success', 'Notifications deleted successfully'];
        return back()->withNotify($notify);
    }

    public function deleteSingleNotification($id)
    {
        AdminNotification::where('id', $id)->delete();
        $notify[] = ['success', 'Notification deleted successfully'];
        return back()->withNotify($notify);
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function groupExpert()
    {

        $users = User::whereHas('referrals', function ($query) {
            $query->havingRaw('COUNT(*) >= 1');
        })->withCount('referrals')->get();

        $expertIds=[];
        foreach ($users as $user){
            $refIds=$user->referrals()->pluck('id');
            $stockMemberShip=StockMember::whereIn('user_id', $refIds)->count();
            if($stockMemberShip && $stockMemberShip >= 10){
                $expertIds[]=  $user->id;
            }
        }

        $eligible_users=User::whereIn('id', $expertIds)->paginate();

        $data['users']=$eligible_users;
        $data['pageTitle']='Group Expert';
        return view('admin.users.group_expert', $data);

    }

    public function assignGgroupExpert(Request $request)
    {

        if (!$request->user_id) {
            return redirect()->back()->withErrors(['errors' => 'Enter a valid user']);
        }

        DB::beginTransaction();


        try {
            $user = User::findOrFail($request->user_id);

            $counter = 0;
            $groupCountNumber = User::where('group_expert', 'yes')->count();
            if ($groupCountNumber) {
                $counter = $groupCountNumber;
                $counter++;
            }

            $currency=Currency::where('symbol', 'USDT')->first();
            if(!$currency){
                $notify[] = ['error', 'Something went wrong'];
                return redirect()->back()->withNotify($notify);
            }



            $wallet=Wallet::where('user_id', $user->id)->where('currency_id', $currency->id)->first();
            if(!$wallet){

                $notify[] = ['error', 'Something went wrong'];
                return redirect()->back()->withNotify($notify);
            }


            $old_balance = $wallet->balance;
            $wallet->balance= $old_balance + 60;
            $wallet->save();



            $transaction = new Transaction();
            $transaction->trx_type = '+';
            $transaction->remark   = 'balance_add';
            $transaction->user_id      = $user->id;
            $transaction->wallet_id    = $wallet->id;
            $transaction->amount       = 60;
            $transaction->post_balance = $wallet->balance;
            $transaction->charge       = 0;
            $transaction->trx          = Str::random(16);
            $transaction->details      = $request->remark;
            $transaction->save();


            $user->group_expert = 'yes';
            $user->group_expert_id = $counter;
            $user->save();


            $message = 'Congratulations! Successfully assign user as a group expert';
            $notify[] = ['success', $message];

            DB::commit();

            return redirect()->back()->withNotify($notify);

        }catch (\Exception $exception){
            DB::rollBack();
            $notify[] = ['error', 'Something went wrong'];
            return redirect()->back()->withNotify($notify);
        }

    }

    public function newNotification(Request $request)
    {

        if($request->time){
            $notifications=AdminNotification::where('is_read', '0')->where('id', '>', $request->time)->get();
        }else{
            $notifications=AdminNotification::where('is_read', '0')->get();
        }


        if($notifications->isNotEmpty()) {

            $data = [];
            foreach ($notifications as $notification) {
                $data[] = [
                    'route' => route('admin.notification.read', $notification->id),
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'created_at' => diffForHumans($notification->created_at),
                    'time' => $notification->created_at,
                ];
            }

            return response()->json(['data' => $data, 'status' => 'success']);
        }else{
            return response()->json(['data'=>[], 'status'=>'failed']);
        }


    }
}
