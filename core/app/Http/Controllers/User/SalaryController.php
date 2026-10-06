<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use App\Models\User;
use App\Models\UserStock;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function salary()
    {
        $user=auth()->user();

//        if($user->group_expert !='yes'){
//            return redirect()->back()->withErrors(['errors' => 'Sorry! You can\'t process this action']);
//        }

        $salaryPending=Salary::where('user_id', $user->id)->where('status', 'pending')->first();
        if($salaryPending){
            return redirect()->back()->withErrors(['errors' => 'Already have a pending salary request']);
        }


        $salaryApproved=Salary::where('user_id', $user->id)->where('status', 'approved')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->first();
        if($salaryApproved){
            return redirect()->back()->withErrors(['errors' => 'This month you have already approved a salary']);
        }

        if(!$user->allReferrals){
            $notify[] = ['success', 'No User Found'];
            return back()->withNotify($notify);
        }


        return \Inertia\Inertia::render('User/Salary', [
            'members' => $user->allReferrals->map(fn ($u) => ['id' => $u->id, 'username' => $u->username, 'name' => $u->fullname])->values(),
            'urls'    => ['invest' => route('user.salary.invest'), 'submit' => route('user.salary.submit')],
        ]);

    }

    public function submitSalary(Request $request)
    {

        $request->validate([
            'team_one'=>'required',
            'team_two'=>'required',
            'team_three'=>'required',
            'team_four'=>'required',
        ]);



        $user=auth()->user();



        $oneTUser=$user->allReferrals->where('id',  $request->team_one)->first();
        $twoTUser=$user->allReferrals->where('id',  $request->team_two)->first();
        $threeTUser=$user->allReferrals->where('id',  $request->team_three)->first();
        $fourTUser=$user->allReferrals->where('id',  $request->team_four)->first();


        if(!$oneTUser || !$twoTUser || !$threeTUser || !$fourTUser){
            return redirect()->back()->withErrors(['errors' => 'Sorry! Something went wrong']);
        }
        $team_member=[
            'Team A 50% from '.$oneTUser->username,
            'Team B 30% from '.$twoTUser->username,
            'Team C 15% from '.$threeTUser->username,
            'Team D 5% from '.$fourTUser->username,
        ];

        

        if($user->group_expert !='yes'){
            return redirect()->back()->withErrors(['errors' => 'Sorry! You can\'t process this action']);
        }

        $salaryPending=Salary::where('user_id', $user->id)->where('status', 'pending')->first();
        if($salaryPending){
            return redirect()->back()->withErrors(['errors' => 'Already have a pending salary request']);
        }


        $salaryApproved=Salary::where('user_id', $user->id)->where('status', 'approved')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->first();

        if($salaryApproved){
            return redirect()->back()->withErrors(['errors' => 'This month you have already approved a salary']);
        }




        $othersField=[];
        $allInvest=0;
            $oneInvest = $this->investOne($user->id, $request->team_one);
            if($oneInvest) {
                $percentOne=50;
                $oneInvest=($oneInvest * $percentOne) / 100;
                $othersField['team_one_invest']=$oneInvest;
                $allInvest=$allInvest + $oneInvest;
            }



            $twoInvest = $this->investOne($user->id, $request->team_two);
            if($twoInvest) {
                $percentTwo=30;
                $twoInvest=($twoInvest * $percentTwo) / 100;
                $othersField['team_two_invest']=$twoInvest;
                $allInvest=$allInvest + $twoInvest;
            }



            $threeInvest = $this->investOne($user->id, $request->team_three);
            if($threeInvest) {
                $percentThree=15;
                $threeInvest=($threeInvest * $percentThree) / 100;
                $othersField['team_three_invest']=$threeInvest;
                $allInvest=$allInvest + $threeInvest;
            }


            $fourInvest = $this->investOne($user->id, $request->team_four);

            if($fourInvest) {
                $percentFour=5;
                $fourInvest=($fourInvest * $percentFour) / 100;
                $othersField['team_four_invest']=$fourInvest;
                $allInvest=$allInvest + $fourInvest;
            }




        $selectedTeams = [
            $request->team_one,
            $request->team_two,
            $request->team_three,
            $request->team_four,
        ];

        if (count($selectedTeams) !== count(array_unique($selectedTeams))) {
            return redirect()->back()->withErrors(['errors' => 'Please choose different teams.']);
        }

        if($allInvest < 6000){
            return redirect()->back()->withErrors(['errors' => 'Invest is less than 6000']);
        }





        $salary= new Salary();
        $salary->user_id=$user->id;
        $salary->others=json_encode($othersField);
        $salary->team_member=json_encode($team_member);
        $salary->amount=$allInvest;
        $salary->save();



        $message = 'Congratulations! Salary Request Successfully Submitted.';
        $notify[] = ['success', $message];
        return redirect()->route('user.home')->withNotify($notify);
    }




    public function salaryInvest(Request $request)
    {

        $user=auth()->user();

        if(!$request->user_id || !$request->type){
            return response()->json(['status'=>'failed', 'message'=>'Please choose a valid user first']);
        }

        if($request->type=='one') {
            $oneInvest = $this->investOne($user->id, $request->user_id);

            if($oneInvest) {

                $percentOne=50;
                $oneInvest=($oneInvest * $percentOne) / 100;


                return response()->json(['status' => 'success', 'data' => $oneInvest]);
            }else{
                return response()->json(['status' => 'failed', 'data' => 0]);
            }
        }


        if($request->type=='two') {
            $twoInvest = $this->investOne($user->id, $request->user_id);

            if($twoInvest) {

                $percentTwo=30;
                $twoInvest=($twoInvest * $percentTwo) / 100;


                return response()->json(['status' => 'success', 'data' => $twoInvest]);
            }else{
                return response()->json(['status' => 'failed', 'data' => 0]);
            }
        }

        if($request->type=='three') {
            $threeInvest = $this->investOne($user->id, $request->user_id);

            if($threeInvest) {

                $percentThree=15;
                $threeInvest=($threeInvest * $percentThree) / 100;


                return response()->json(['status' => 'success', 'data' => $threeInvest]);
            }else{
                return response()->json(['status' => 'failed', 'data' => 0]);
            }
        }

        if($request->type=='four') {
            $fourInvest = $this->investOne($user->id, $request->user_id);

            if($fourInvest) {

                $percentFour=5;
                $fourInvest=($fourInvest * $percentFour) / 100;


                return response()->json(['status' => 'success', 'data' => $fourInvest]);
            }else{
                return response()->json(['status' => 'failed', 'data' => 0]);
            }
        }

    }


    function investOne($user_id, $ref_id)
    {

        $user=User::find($user_id);

        $mainRef=$user->allReferrals->where('id', $ref_id)->first();

        if(!$mainRef){
            return false;
        }

       $refOnes= $mainRef->allReferrals;

       if($refOnes){
           $mainInvest=UserStock::where('user_id', $ref_id)->where('status', 'buy')->sum('invest_amount');
           $countAllInvest=$mainInvest?$mainInvest:0;


           //Team One
           foreach($refOnes  as $refOne){
               $teamOneInvest=UserStock::whereIn('user_id', $refOne->referrals->pluck('id'))->where('status', 'buy')->sum('invest_amount');
               $countAllInvest= $countAllInvest + $teamOneInvest;

               if($refOne->allReferrals){
                   //Team Two
                   foreach($refOne->allReferrals as $refTwo){
                       $teamTwoInvest=UserStock::whereIn('user_id', $refTwo->referrals->pluck('id'))->where('status', 'buy')->sum('invest_amount');
                       $countAllInvest= $countAllInvest + $teamTwoInvest;

                       if($refTwo->allReferrals){
                           //Team Three
                           foreach($refTwo->allReferrals as $refThree){
                               $teamThreeInvest=UserStock::whereIn('user_id', $refThree->referrals->pluck('id'))->where('status', 'buy')->sum('invest_amount');
                               $countAllInvest= $countAllInvest + $teamThreeInvest;


                               if($refThree->allReferrals){
                                   //Team Four
                                   foreach($refThree->allReferrals as $refFour){
                                       $teamFourInvest=UserStock::whereIn('user_id', $refFour->referrals->pluck('id'))->where('status', 'buy')->sum('invest_amount');
                                       $countAllInvest= $countAllInvest + $teamFourInvest;

                                       if($refFour->allReferrals){
                                           //Team Four
                                           foreach($refFour->allReferrals as $refFive){
                                               $teamFiveInvest=UserStock::whereIn('user_id', $refFive->referrals->pluck('id'))->where('status', 'buy')->sum('invest_amount');
                                               $countAllInvest= $countAllInvest + $teamFiveInvest;

                                           }
                                       }
                                   }
                               }
                           }
                       }
                   }
               }
           }
       }

       return $countAllInvest;

    }

}
