<?php

namespace App\Http\Controllers;

use App\Job\SendStateMentEmail;
use App\Models\StockMember;
use App\Models\User;
use App\Models\UserStock;
use App\Models\WeeklyStatement;
use Illuminate\Http\Request;

class StatementController extends Controller
{

    public function miss(){

        $pndStock=UserStock::whereDate('interest_date', '<', '2025-03-26')->where('status', 'buy')->limit(1)->get();

        foreach ($pndStock as $pndS){
            $pndS->interest_date=now();
            $pndS->save();
        }

        dd($pndStock);

    }

    public function sendStatement(){


        $fixDate=now();

        $ableDateData=WeeklyStatement::whereDate('next_date', $fixDate)->first();

        if($ableDateData){

            $stockMembersIds=StockMember::where('status','approve')->pluck('user_id');

            $all_users=User::where('status', '1')->whereIn('id', $stockMembersIds)->select(['id','firstname','lastname','email'])->get();

            if($all_users->isNotEmpty()){

                foreach($all_users as $usr){
                    SendStateMentEmail::dispatch($usr->id, $usr->email, $usr->fullname);
                }

            }

            $ableDateData->next_date=now()->addWeek();
            $ableDateData->save();

        }


    }




}
