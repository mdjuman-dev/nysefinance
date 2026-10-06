<?php

namespace App\Http\Controllers\User;

use App\Support\InertiaData;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;

class DepositController extends Controller
{

    public function pendingRequest()
    {

        $pageTitle='Deposit Request';
        $deposits=Deposit::where('user_id', auth()->user()->id)->orderByDesc('created_at')->paginate(getPaginate());

        return Inertia::render('User/DepositRequests', [
            'deposits' => InertiaData::paginate($deposits, fn ($d) => [
                'id'       => $d->id,
                'amount'   => (float) $d->amount,
                'charge'   => (float) $d->charge,
                'currency' => $d->method_currency,
                'method'   => $d->method_code,
                'status'   => $d->status == '1' ? 'Success' : ($d->status == '2' ? 'Pending' : 'Rejected'),
                // the Blade view enabled "view" only for status === 'pending' (string); kept as-is
                'viewUrl'  => $d->status == 'pending' ? route('user.deposit.ch', [$d->id]) : null,
                'date'     => InertiaData::date($d->created_at),
            ]),
        ]);




    }

    public function checkOutPage($id)
    {

        $deposit=Deposit::where('id', $id)->where('user_id', auth()->user()->id)->firstOrFail();

        if($deposit->status !='2'){
            $notifye[] = ['error', 'This deposit request is not pending yet.'];
            return redirect()->back()->withNotify($notifye);
        }


        $crypto_address=$deposit->address;

        $deposit_id=$deposit->id;

        $amount=$deposit->amount;

        return view('crypto_pay', compact('crypto_address','deposit_id','amount'));


    }
}
