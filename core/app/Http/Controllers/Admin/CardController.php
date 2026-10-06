<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardApplication;
use Illuminate\Http\Request;

class CardController extends Controller
{


    public function index()
    {

        $pageTitle = "Card Application";

        $applications = CardApplication::orderByDesc('created_at')->paginate(getPaginate());

        return view('admin.card_application.list', compact('pageTitle', 'applications'));

    }


    public function details($id)
    {

        $pageTitle = "Card Application Details";

        $application = CardApplication::where('id', $id)->firstOrFail();

        return view('admin.card_application.details', compact('pageTitle', 'application'));

    }

}
