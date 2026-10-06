<?php

namespace App\Http\Controllers;

use App\Models\UserReport;
use Illuminate\Http\Request;

class ReportAutoResolveController extends Controller
{


    public function index(){

        $user_reports=UserReport::where('status','pending')->whereIn('type', ['wrong_password','forget_password'])->get();


        if($user_reports){
            foreach($user_reports as $user_report){
                $diffHours=$user_report->created_at->diffInHours(\Carbon\Carbon::now());

                if ($diffHours >= 24) {
                    $user_report->status='solve';
                    $user_report->save();
                }

            }
        }

    }
}
