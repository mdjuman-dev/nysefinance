<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LaunchPad;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class LaunchPadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Launch Pads';
        $launchPads = LaunchPad::latest()->paginate(getPaginate());
        return view('admin.launchpad.list', compact('launchPads','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Add New LaunchPad';
        return view('admin.launchpad.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_currency' => 'required|string|max:10',
            'total_allocation' => 'required|integer|min:0',
            'cap_per_subscriber' => 'required|integer|min:0',
            'total_committed_amount' => 'required|integer|min:0',
            'status' => 'required|string|in:active,upcoming,closed',
            'details_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $launchPad = new LaunchPad();
            $launchPad->title = $request->title;
            $launchPad->sub_title = $request->sub_title;
            $launchPad->slug = Str::slug($request->title);
            $launchPad->description = $request->description;
            $launchPad->price = $request->price;
            $launchPad->price_currency = $request->price_currency;
            $launchPad->total_allocation = $request->total_allocation;
            $launchPad->cap_per_subscriber = $request->cap_per_subscriber;
            $launchPad->total_committed_amount = $request->total_committed_amount;
            $launchPad->status = $request->status;
            $launchPad->details_url = $request->details_url;

            if ($request->hasFile('image')) {
                $path = getFilePath('currency');
                $size = getFileSize('currency');
                try {
                    $filename = fileUploader($request->image, $path, $size);
                    $launchPad->image = $filename;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your image'];
                    return back()->withNotify($notify);
                }
            }

            $launchPad->save();

            return redirect()->route('admin.launchpad.index')->with('success', 'LaunchPad created successfully');
        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Error creating LaunchPad: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit LaunchPad';
        try {
            $launchPad = LaunchPad::findOrFail($id);
            return view('admin.launchpad.edit', compact('launchPad', 'pageTitle'));
        } catch (\Exception $e) {
            return redirect()->route('admin.launchpad.index')->with('error', 'LaunchPad not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {


        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'price_currency' => 'required|string|max:10',
            'total_allocation' => 'required|integer|min:0',
            'cap_per_subscriber' => 'required|integer|min:0',
            'total_committed_amount' => 'required|integer|min:0',
            'status' => 'required|string|in:active,upcoming,closed',
            'details_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        try {
            $launchPad = LaunchPad::findOrFail($id);
            $launchPad->title = $request->title;
            $launchPad->sub_title = $request->sub_title;
            $launchPad->slug = Str::slug($request->title);
            $launchPad->description = $request->description;
            $launchPad->price = $request->price;
            $launchPad->price_currency = $request->price_currency;
            $launchPad->total_allocation = $request->total_allocation;
            $launchPad->cap_per_subscriber = $request->cap_per_subscriber;
            $launchPad->total_committed_amount = $request->total_committed_amount;
            $launchPad->status = $request->status;
            $launchPad->details_url = $request->details_url;

            if ($request->hasFile('image')) {
                $path = getFilePath('currency');
                $size = getFileSize('currency');
                try {
                    $filename = fileUploader($request->image, $path, $size, @$launchPad->image);
                    $launchPad->image = $filename;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your image'];
                    return back()->withNotify($notify);
                }
            }

            $launchPad->save();

            return redirect()->route('admin.launchpad.index')->with('success', 'LaunchPad updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating LaunchPad: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function del($id)
    {
        try {
            $launchPad = LaunchPad::findOrFail($id);
            $launchPad->delete();
            return redirect()->route('admin.launchpad.index')->with('success', 'LaunchPad deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.launchpad.index')->with('error', 'Error deleting LaunchPad: ' . $e->getMessage());
        }
    }
}
