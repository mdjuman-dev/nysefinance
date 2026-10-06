<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Transaction;
use App\Models\UserReport;
use Illuminate\Http\Request;

class UserReportController extends Controller
{



    public function index(Request $request)
    {

        $reports=UserReport::orderByDesc('created_at')->paginate(getPaginate());

        $pageTitle='User Reports';

        return view('admin.user_report.list', compact('pageTitle', 'reports'));
    }

    public function status(Request $request)
    {

        $request->validate([
            'status' => 'required|in:solved,review',
        ]);

        $report=UserReport::where('id', $request->id)->firstOrFail();

        $report->status=$request->status;
        $report->save();


        return redirect()->back()->with('success', 'Report status updated successfully.');

    }



}
