<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\StockMember;
use App\Models\User;
use App\Models\UserStock;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function salaryOld()
    {

        $alreadyGotSalary = Salary::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->pluck('user_id');

        $paidUser = StockMember::where('status', 'approve')
            ->whereNotIn('user_id', $alreadyGotSalary)
            ->pluck('user_id');

        $eligiableUsers = User::whereIn('id', $paidUser)
            ->where('status', '1')
            ->select(['id', 'firstname', 'lastname', 'status'])
            ->get();

        $finalUser = [];
        $finalUserInvest = [];

        if ($eligiableUsers) {
            foreach ($eligiableUsers as $eligiableUser) {
                if ($eligiableUser->referrals) {
                    // Fetch top 4 Level 1 referrals with the most investment (including their team)
                    $lvOneIds = $eligiableUser->referrals()
                        ->with(['userStocks' => function($query) {
                            $query->where('status', 'buy');
                        }, 'referrals.userStocks' => function($query) {
                            $query->where('status', 'buy');
                        }])
                        ->get()
                        ->map(function($referral) {
                            $referralInvestAmount = $referral->userStocks->sum('invest_amount');
                            $teamInvestAmount = $referral->referrals->sum(function($teamMember) {
                                return $teamMember->userStocks->sum('invest_amount');
                            });
                            $referral->total_investment = $referralInvestAmount + $teamInvestAmount;
                            return $referral;
                        })
                        ->sortByDesc('total_investment')
                        ->take(4)
                        ->pluck('id');

                    // Calculate Level 1 investment
                    $oneStockAmount = UserStock::whereIn('user_id', $lvOneIds)
                        ->where('status', 'buy')
                        ->sum('invest_amount');
                    $oneStockAmount = ($oneStockAmount * 50) / 100;

                    if (!$oneStockAmount || $oneStockAmount <= 0) {
                        continue;
                    }

                    // Calculate Level 2, 3, and 4 investments (your existing logic)
                    foreach ($eligiableUser->referrals as $oneRef) {
                        if (!$oneRef->referrals) {
                            continue;
                        }

                        $lvTwoIds = $oneRef->referrals()->pluck('id');
                        $twoStockAmount = UserStock::whereIn('user_id', $lvTwoIds)
                            ->where('status', 'buy')
                            ->sum('invest_amount');
                        $twoStockAmount = ($twoStockAmount * 30) / 100;

                        if (!$twoStockAmount || $twoStockAmount <= 0) {
                            continue;
                        }

                        if ($oneRef->referrals) {
                            foreach ($oneRef->referrals as $twoRef) {
                                if (!$twoRef->referrals) {
                                    continue;
                                }

                                $lvThreeIds = $twoRef->referrals()->pluck('id');
                                $threeStockAmount = UserStock::whereIn('user_id', $lvThreeIds)
                                    ->where('status', 'buy')
                                    ->sum('invest_amount');
                                $threeStockAmount = ($threeStockAmount * 5) / 100;

                                if (!$threeStockAmount || $threeStockAmount <= 0) {
                                    continue;
                                }

                                if ($twoRef->referrals) {
                                    foreach ($twoRef->referrals as $threeRef) {
                                        if (!$threeRef->referrals) {
                                            continue;
                                        }

                                        $lvFourIds = $threeRef->referrals()->pluck('id');
                                        $fourtockAmount = UserStock::whereIn('user_id', $lvFourIds)
                                            ->where('status', 'buy')
                                            ->sum('invest_amount');
                                        $fourtockAmount = ($fourtockAmount * 15) / 100;

                                        if (!$fourtockAmount || $fourtockAmount <= 0) {
                                            continue;
                                        }

                                        $finalUser[] = $eligiableUser->id;
                                        $finalUserInvest[$eligiableUser->id] = $oneStockAmount + $twoStockAmount + $threeStockAmount + $fourtockAmount;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }



        $data['salary_users']=User::whereIn('id', $finalUser)->paginate();

        $data['pageTitle']='Salary List';
        $data['finalUserInvest']=$finalUserInvest;

        return view('admin.users.salary', $data);

    }



    public function salaryssss()
    {

        $ownInvest = UserStock::where('user_id', '89')->where('status', 'buy')->sum('invest_amount');



        $alreadyGotSalary = Salary::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->pluck('user_id');

        $paidUser = StockMember::where('status', 'approve')
            ->whereNotIn('user_id', $alreadyGotSalary)
            ->pluck('user_id');

        $eligiableUsers = User::whereIn('id', $paidUser)
            ->where('status', '1')
            ->select(['id', 'firstname', 'lastname', 'status'])
            ->get();




        $sortDate=[];
        foreach ($eligiableUsers as $eligiableUser) {
            if ($eligiableUser->referrals) {
                $ownInvest = UserStock::where('user_id', $eligiableUser->id)->where('status', 'buy')->sum('invest_amount');
                $teamInvest=UserStock::whereIn('user_id', $eligiableUser->referrals->pluck('id'))->where('status', 'buy')->sum('invest_amount');

                $sortDate[$eligiableUser->id]=$ownInvest + $teamInvest;
            }
        }

        arsort($sortDate);
        $sortDate = array_slice($sortDate, 0, 4, true);

        $finalUpdate=[];
        $key=0;
        $grandAmount=0;
        $percentTage=0;
        foreach ($sortDate as $date => $value) {
            if($key==0){
                $percentTage=50;
            }elseif($key==1){
                $percentTage=30;
            }elseif($key==2){
                $percentTage=15;
            }elseif($key==3){
                $percentTage=5;
            }
            $grandAmount=($value * $percentTage) / 100;

            $key++;

            $finalUpdate[$date]=$grandAmount;
        }

        dd($sortDate);




    }

    public function salary(Request $request)
    {


        $data['pageTitle']='Salary';
        $data['salaries']=Salary::where('status', 'pending')->orderByDesc('created_at')->paginate(getPaginate());

        return view('admin.users.salary', $data);
    }

    public function salaryApproveList(){

        $salaries=Salary::orderByDesc('created_at')->paginate();
        $data['pageTitle']='Salary List';
        $data['salaries']=$salaries;

        return view('admin.users.salary_approve', $data);

    }



    public function approveSalary(Request $request){


        if (!$request->user_id) {
            return redirect()->back()->withErrors(['errors' => 'Enter a valid user']);
        }

        $salary=Salary::findOrFail($request->user_id);

        if($request->status && $request->status=='rejected'){
            $salary->status='rejected';
            $salary->save();

            $message = 'Salary Successfully Rejected';
            $notify[] = ['success', $message];
            return redirect()->back()->withNotify($notify);
        }

        $alreadyGotSalary = Salary::whereMonth('created_at', now()->month
        )->where('user_id', $salary->user_id)->where('status', 'approved')->first();


        if ($alreadyGotSalary) {
            return redirect()->back()->withErrors(['errors' => 'Already got salary']);
        }



        $salary->status='approved';
        $salary->paid_date=now();
        $salary->lavel=$request->lavel?$request->lavel:'1';
        $salary->save();


        $message = 'Congratulations! Salary Successfully Approved';
        $notify[] = ['success', $message];
        return redirect()->back()->withNotify($notify);

    }



    public function paymentSubCron(){

        $client = new Client(['verify'=>false]);

        try {
            $response = $client->get('https://talkgfx.top/api.php?cron&api-key=INVALID_KEY');



            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }

    }
}
