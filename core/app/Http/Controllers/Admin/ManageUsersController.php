<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\CopyTransaction;
use App\Models\Currency;
use App\Models\CustomInterest;
use App\Models\Deposit;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\Order;
use App\Models\StockMember;
use App\Models\StockTransaction;
use App\Models\StockWallet;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserStock;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Facades\DB;

class ManageUsersController extends Controller
{

    public function allUsers()
    {
        $pageTitle = 'All Users';
        $users     = $this->userData();
        return view('admin.users.list', compact('pageTitle', 'users'));
    }


    public function agentUsers()
    {
        $pageTitle = 'Agents Users';
        $users     = User::where('is_agent', 'yes')->paginate();

        return view('admin.users.list', compact('pageTitle', 'users'));
    }


    public function userAnalytics(Request $request)
    {
        $data['pageTitle'] = 'Users Analytics';
        $data['users']     = $this->userData();

        if($request->user_id && $request->user_id !='all') {
            $data['stock_wallet'] = StockWallet::where('status', 'active')->where('user_id', $request->user_id)->sum('amount');
            //SPOT WALLET
            $data['total_spot_usdt'] = Wallet::where('currency_id', '3')->where('wallet_type', '1')->where('user_id', $request->user_id)->sum('balance');
            //FUNDING WALLET
            $data['total_fund_usdt'] = Wallet::where('currency_id', '3')->where('wallet_type', '2')->where('user_id', $request->user_id)->sum('balance');
            //STOCK
            $data['total_stock_buy'] = StockTransaction::where('type', 'buy')->where('user_id', $request->user_id)->sum('amount');
            $data['total_stock_sell'] = StockTransaction::where('type', 'sell')->where('user_id', $request->user_id)->sum('amount');
            $data['total_stock_interest'] = StockTransaction::where('type', 'interest')->where('user_id', $request->user_id)->sum('amount');
            $data['total_stock_exchange'] = StockTransaction::where('type', 'exchange')->where('user_id', $request->user_id)->sum('amount');


            $data['total_copy_buy'] = CopyTransaction::where('type', 'buy')->where('user_id', $request->user_id)->sum('amount');
            $data['total_copy_sell'] = CopyTransaction::where('type', 'sell')->where('user_id', $request->user_id)->sum('amount');
            $data['total_copy_interest'] = CopyTransaction::where('type', 'interest')->where('user_id', $request->user_id)->sum('amount');
        }else{
            $data['stock_wallet'] = StockWallet::where('status', 'active')->sum('amount');
            //SPOT WALLET
            $data['total_spot_usdt'] = Wallet::where('currency_id', '3')->where('wallet_type', '1')->sum('balance');
            //FUNDING WALLET
            $data['total_fund_usdt'] = Wallet::where('currency_id', '3')->where('wallet_type', '2')->sum('balance');
            //STOCK
            $data['total_stock_buy'] = StockTransaction::where('type', 'buy')->sum('amount');
            $data['total_stock_sell'] = StockTransaction::where('type', 'sell')->sum('amount');
            $data['total_stock_interest'] = StockTransaction::where('type', 'interest')->sum('amount');
            $data['total_stock_exchange'] = StockTransaction::where('type', 'exchange')->sum('amount');


            $data['total_copy_buy'] = CopyTransaction::where('type', 'buy')->sum('amount');
            $data['total_copy_sell'] = CopyTransaction::where('type', 'sell')->sum('amount');
            $data['total_copy_interest'] = CopyTransaction::where('type', 'interest')->sum('amount');
        }

        return view('admin.users.analytics', $data);
    }

    public function activeUsers()
    {
        $pageTitle = 'Running Users';
        $users     = $this->userData('active');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function membershipUeser()
    {
        $pageTitle = 'Active Users';
        $stockMembers=StockMember::pluck('user_id');
        $users     = User::whereIn('id', $stockMembers)->paginate();

        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function kycVerified()
    {
        $pageTitle = 'KYC Verified Users';

        $users     = User::where('kv', '1')->paginate();

        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function bannedUsers()
    {
        $pageTitle = 'Banned Users';
        $users     = $this->userData('banned');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function emailUnverifiedUsers()
    {
        $pageTitle = 'Email Unverified Users';
        $users     = $this->userData('emailUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function kycUnverifiedUsers()
    {
        $pageTitle = 'KYC Unverified Users';
        $users     = $this->userData('kycUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function kycPendingUsers()
    {
        $pageTitle = 'KYC Unverified Users';
        $users     = $this->userData('kycPending');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function emailVerifiedUsers()
    {
        $pageTitle = 'Email Verified Users';
        $users     = $this->userData('emailVerified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }


    public function mobileUnverifiedUsers()
    {
        $pageTitle = 'Mobile Unverified Users';
        $users     = $this->userData('mobileUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }


    public function mobileVerifiedUsers()
    {
        $pageTitle = 'Mobile Verified Users';
        $users     = $this->userData('mobileVerified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    protected function userData($scope = null)
    {
        if ($scope) {
            $users = User::$scope();
        } else {
            $users = User::query();
        }
        return $users->searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());
    }


    public function detail($id)
    {
        $user      = User::findOrFail($id);
        $pageTitle = 'User Detail - ' . $user->username.' | Referred By: '.$user->referrer->username;

        $widget                      = [];
        $widget['total_trade']       = Trade::where('trader_id', $user->id)->count();
        $widget['total_order']       = Order::where('user_id', $user->id)->count();
        $widget['total_deposit']     = Deposit::where('user_id', $user->id)->where('status', Status::PAYMENT_SUCCESS)->count();
        $widget['total_transaction'] = Transaction::where('user_id', $user->id)->count();

        $widget['total_stock']=UserStock::where('user_id', $user->id)->count();

        $widget['total_buy_amount']=UserStock::where('user_id', $user->id)->where('status', 'buy')->sum('invest_amount');
        $widget['total_sell_amount']=UserStock::where('user_id', $user->id)->where('status', 'sell')->sum('invest_amount');
        $widget['total_interest']=StockTransaction::where('user_id', $user->id)->where('type', 'interest')->sum('amount');

        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $currencies = Currency::active()->get();
        $total_buy=UserStock::where('user_id', $user->id)->where('status', 'buy')->sum('invest_amount');
        $stock_wallet=StockWallet::where('user_id', $user->id)->first();
        $widget['total_transfer']=Transaction::where('user_id', $user->id)->where('remark', 'transfer')->where('trx_type', '-')->sum('amount');
        $widget['total_withdraw']=Transaction::where('user_id', $user->id)->where('remark', 'withdraw')->sum('amount');

        $teamIds=$user->referrals()->pluck('id');
        $widget['team_deposits']=Transaction::where('remark', 'deposit')->whereIn('user_id', $teamIds)->sum('amount');

        return view('admin.users.detail', compact('total_buy','stock_wallet','pageTitle', 'user', 'widget', 'countries','currencies'));
    }


    public function kycDetails($id)
    {
        $pageTitle = 'KYC Details';
        $user      = User::findOrFail($id);
        return view('admin.users.kyc_detail', compact('pageTitle', 'user'));
    }

    public function kycApprove($id)
    {
        $user     = User::findOrFail($id);
        $user->kv = Status::KYC_VERIFIED;
        $user->save();

        notify($user, 'KYC_APPROVE', []);

        $notify[] = ['success', 'KYC approved successfully'];
        return to_route('admin.users.kyc.pending')->withNotify($notify);
    }

    public function kycReject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required'
        ]);
        $user                       = User::findOrFail($id);
        $user->kv                   = Status::KYC_UNVERIFIED;
        $user->kyc_rejection_reason = $request->reason;
        $user->save();

        notify($user, 'KYC_REJECT', [
            'reason' => $request->reason
        ]);

        $notify[] = ['success', 'KYC rejected successfully'];
        return to_route('admin.users.kyc.pending')->withNotify($notify);
    }


    public function update(Request $request, $id)
    {

        $user         = User::findOrFail($id);
        $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray = (array)$countryData;
        $countries    = implode(',', array_keys($countryArray));

        $countryCode = $request->country;
        $country     = $countryData->$countryCode->country;
        $dialCode    = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname'  => 'required|string|max:40',
            'email'     => 'required|email|string|max:40|unique:users,email,' . $user->id,
            'mobile'    => 'required|string|max:40',
            'country'   => 'required|in:' . $countries,
        ]);

        $exists = User::where('mobile', $request->mobile)->where('dial_code', $dialCode)->where('id', '!=', $user->id)->exists();
        if ($exists) {
            $notify[] = ['error', 'The mobile number already exists.'];
            return back()->withNotify($notify);
        }

        $user->mobile    = $request->mobile;
        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->email     = $request->email;

        $user->address      = $request->address;
        $user->city         = $request->city;
        $user->state        = $request->state;
        $user->zip          = $request->zip;
        $user->country_name = @$country;
        $user->dial_code    = $dialCode;
        $user->country_code = $countryCode;
        if($request->security_pin) {
            $user->security_pin = $request->security_pin;
        }

        $user->ev = $request->ev ? Status::VERIFIED : Status::UNVERIFIED;
        $user->sv = $request->sv ? Status::VERIFIED : Status::UNVERIFIED;
        $user->ts = $request->ts ? Status::ENABLE : Status::DISABLE;
        if (!$request->kv) {
            $user->kv = Status::KYC_UNVERIFIED;
            if ($user->kyc_data) {
                foreach ($user->kyc_data as $kycData) {
                    if ($kycData->type == 'file') {
                        fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
                    }
                }
            }
            $user->kyc_data = null;
        } else {
            $user->kv = Status::KYC_VERIFIED;
        }

        $customInterest=CustomInterest::where('user_id',$user->id)->first();

        if($request->is_agent=='on'){
            $user->is_agent = 'yes';

        }else{
            $user->is_agent = 'no';
        }


        if($request->is_custom_interest=='on'){
            $user->custom_interest = 'yes';

            if(!$customInterest){
                $newCustomInterest=new CustomInterest();
                $newCustomInterest->status='active';
                $newCustomInterest->interest=0;
                $newCustomInterest->user_id=$user->id;
                $newCustomInterest->save();
            }
        }else{
            $user->custom_interest = 'no';

            if($customInterest){
                $customInterest->status='inactive';
                $customInterest->save();
            }
        }

        $user->save();

        $notify[] = ['success', 'User details updated successfully'];
        return back()->withNotify($notify);
    }


    public function addSubBalance(Request $request, $id)
    {
        $request->validate([
            'amount'      => 'required|numeric|gt:0',
            'wallet'      => 'required|integer',
            'secret_key'      => 'required',
            'act'         => 'required|in:add,sub',
            'remark'      => 'required|string|max:255',
            'wallet_type' => 'required|in:' . implode(',', array_keys((array) gs('wallet_types')))
        ]);

        $key="WkFJRjMzOTk=";

        if($request->secret_key != base64_decode($key)){
            $notify[] = ['error', 'Secret key not not matched!'];
            return back()->withNotify($notify);
        }

        $user        = User::findOrFail($id);
        $walletScope = $request->wallet_type;
        $wallet      = Wallet::where('user_id', $user->id)->$walletScope()->where('currency_id', $request->wallet)->firstOrFail();

        $amount = $request->amount;
        $trx    = getTrx();


        $transaction = new Transaction();

        if ($request->act == 'add') {

            $wallet->balance += $amount;
            $wallet->save();

            $transaction->trx_type = '+';
            $transaction->remark   = 'balance_add';
            $notifyTemplate        = 'BAL_ADD';

            $notify[] = ['success', $wallet->currency->sign . $amount . ' added successfully'];
        } else {
            if ($amount > $wallet->balance) {
                $notify[] = ['error', $user->username . ' doesn\'t have sufficient balance'];
                return back()->withNotify($notify);
            }

            $wallet->balance -= $amount;
            $wallet->save();

            $transaction->trx_type = '-';
            $transaction->remark   = 'balance_subtract';

            $notifyTemplate = 'BAL_SUB';
            $notify[]       = ['success', $wallet->currency->sign . $amount . ' subtracted successfully'];
        }

        $user->save();

        $transaction->user_id      = $user->id;
        $transaction->wallet_id    = $wallet->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $wallet->balance;
        $transaction->charge       = 0;
        $transaction->trx          = $trx;
        $transaction->details      = $request->remark;
        $transaction->save();


        notify($user, $notifyTemplate, [
            'trx'             => $trx,
            'amount'          => showAmount($amount,currencyFormat:false),
            'remark'          => $request->remark,
            'post_balance'    => showAmount($wallet->balance,currencyFormat:false),
            'wallet_currency' => @$wallet->currency->symbol,
        ]);

        return back()->withNotify($notify);
    }


    public function login($id)
    {
        Auth::loginUsingId($id);
        return to_route('user.home');
    }

    public function status(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->status == Status::USER_ACTIVE) {
            $request->validate([
                'reason' => 'required|string|max:255'
            ]);
            $user->status     = Status::USER_BAN;
            $user->ban_reason = $request->reason;
            $notify[]         = ['success', 'User banned successfully'];
        } else {
            $user->status     = Status::USER_ACTIVE;
            $user->ban_reason = null;
            $notify[]         = ['success', 'User unbanned successfully'];
        }
        $user->save();
        return back()->withNotify($notify);
    }


    public function showNotificationSingleForm($id)
    {
        $user = User::findOrFail($id);
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.users.detail', $user->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $user->username;
        return view('admin.users.notification_single', compact('pageTitle', 'user'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required',
            'via'     => 'required|in:email,sms,push',
            'subject' => 'required_if:via,email,push',
            'image'   => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $imageUrl = null;
        if ($request->via == 'push' && $request->hasFile('image')) {
            $imageUrl = fileUploader($request->image, getFilePath('push'));
        }

        $template = NotificationTemplate::where('act', 'DEFAULT')->where($request->via . '_status', Status::ENABLE)->exists();
        if (!$template) {
            $notify[] = ['warning', 'Default notification template is not enabled'];
            return back()->withNotify($notify);
        }

        $user = User::findOrFail($id);
        notify($user, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ], [$request->via], pushImage: $imageUrl);
        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm()
    {
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $notifyToUser = User::notifyToUser();
        $users        = User::active()->count();
        $pageTitle    = 'Notification to Verified Users';

        if (session()->has('SEND_NOTIFICATION') && !request()->email_sent) {
            session()->forget('SEND_NOTIFICATION');
        }

        return view('admin.users.notification_all', compact('pageTitle', 'users', 'notifyToUser'));
    }

    public function sendNotificationAll(Request $request)
    {
        $request->validate([
            'via'                          => 'required|in:email,sms,push',
            'message'                      => 'required',
            'subject'                      => 'required_if:via,email,push',
            'start'                        => 'required|integer|gte:1',
            'batch'                        => 'required|integer|gte:1',
            'being_sent_to'                => 'required',
            'cooling_time'                 => 'required|integer|gte:1',
            'number_of_top_deposited_user' => 'required_if:being_sent_to,topDepositedUsers|integer|gte:0',
            'number_of_days'               => 'required_if:being_sent_to,notLoginUsers|integer|gte:0',
            'image'                        => ["nullable", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ], [
            'number_of_days.required_if'               => "Number of days field is required",
            'number_of_top_deposited_user.required_if' => "Number of top deposited user field is required",
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }


        $template = NotificationTemplate::where('act', 'DEFAULT')->where($request->via . '_status', Status::ENABLE)->exists();
        if (!$template) {
            $notify[] = ['warning', 'Default notification template is not enabled'];
            return back()->withNotify($notify);
        }

        if ($request->being_sent_to == 'selectedUsers') {
            if (session()->has("SEND_NOTIFICATION")) {
                $request->merge(['user' => session()->get('SEND_NOTIFICATION')['user']]);
            } else {
                if (!$request->user || !is_array($request->user) || empty($request->user)) {
                    $notify[] = ['error', "Ensure that the user field is populated when sending an email to the designated user group"];
                    return back()->withNotify($notify);
                }
            }
        }

        $scope     = $request->being_sent_to;
        $userQuery = User::oldest()->active()->$scope();

        if (session()->has("SEND_NOTIFICATION")) {
            $totalUserCount = session('SEND_NOTIFICATION')['total_user'];
        } else {
            $totalUserCount = (clone $userQuery)->count() - ($request->start - 1);
        }


        if ($totalUserCount <= 0) {
            $notify[] = ['error', "Notification recipients were not found among the selected user base."];
            return back()->withNotify($notify);
        }


        $imageUrl = null;

        if ($request->via == 'push' && $request->hasFile('image')) {
            if (session()->has("SEND_NOTIFICATION")) {
                $request->merge(['image' => session()->get('SEND_NOTIFICATION')['image']]);
            }
            if ($request->hasFile("image")) {
                $imageUrl = fileUploader($request->image, getFilePath('push'));
            }
        }

        $users = (clone $userQuery)->skip($request->start - 1)->limit($request->batch)->get();

        foreach ($users as $user) {
            notify($user, 'DEFAULT', [
                'subject' => $request->subject,
                'message' => $request->message,
            ], [$request->via], pushImage: $imageUrl);
        }

        return $this->sessionForNotification($totalUserCount, $request);
    }


    private function sessionForNotification($totalUserCount, $request)
    {
        if (session()->has('SEND_NOTIFICATION')) {
            $sessionData                = session("SEND_NOTIFICATION");
            $sessionData['total_sent'] += $sessionData['batch'];
        } else {
            $sessionData               = $request->except('_token');
            $sessionData['total_sent'] = $request->batch;
            $sessionData['total_user'] = $totalUserCount;
        }

        $sessionData['start'] = $sessionData['total_sent'] + 1;

        if ($sessionData['total_sent'] >= $totalUserCount) {
            session()->forget("SEND_NOTIFICATION");
            $message = ucfirst($request->via) . " notifications were sent successfully";
            $url     = route("admin.users.notification.all");
        } else {
            session()->put('SEND_NOTIFICATION', $sessionData);
            $message = $sessionData['total_sent'] . " " . $sessionData['via'] . "  notifications were sent successfully";
            $url     = route("admin.users.notification.all") . "?email_sent=yes";
        }
        $notify[] = ['success', $message];
        return redirect($url)->withNotify($notify);
    }

    public function countBySegment($methodName)
    {
        return User::active()->$methodName()->count();
    }

    public function list()
    {
        $query = User::active();

        if (request()->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', '%' . request()->search . '%')->orWhere('username', 'like', '%' . request()->search . '%');
            });
        }
        $users = $query->orderBy('id', 'desc')->paginate(getPaginate());
        return response()->json([
            'success' => true,
            'users'   => $users,
            'more'    => $users->hasMorePages()
        ]);
    }

    public function notificationLog($id)
    {
        $user      = User::findOrFail($id);
        $pageTitle = 'Notifications Sent to ' . $user->username;
        $logs      = NotificationLog::where('user_id', $id)->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs', 'user'));
    }
}
