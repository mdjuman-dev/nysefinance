<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Manage FAQs';
        $faqs = Faq::latest()->paginate(getPaginate());
        return view('admin.faq.list', compact('pageTitle', 'faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add New FAQ';
        return view('admin.faq.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        Faq::create($request->all());

        $notify[] = ['success', 'FAQ added successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit FAQ';
        $faq = Faq::findOrFail($id);
        return view('admin.faq.edit', compact('pageTitle', 'faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'status' => 'required|in:active,inactive'
        ]);

        $faq = Faq::findOrFail($id);
        $faq->update($request->all());

        $notify[] = ['success', 'FAQ updated successfully'];
        return back()->withNotify($notify);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function del($id)
    {

        $faq = Faq::findOrFail($id);
        $faq->delete();

        $notify[] = ['success', 'FAQ deleted successfully'];
        return back()->withNotify($notify);
    }
}
