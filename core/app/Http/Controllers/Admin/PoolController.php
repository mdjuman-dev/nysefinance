<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoinStack;
use App\Models\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Staking Pools';
        $pools = Pool::latest()->paginate(getPaginate());
        return view('admin.pool.list', compact('pageTitle', 'pools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Staking Pool';
        return view('admin.pool.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'apr' => 'required|numeric|min:0|max:1000',
            'vip_apr' => 'required|numeric|min:0|max:1000',
            'prize_pool' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pool = new Pool();
        $pool->name = $request->name;
        $pool->symbol = $request->symbol;
        $pool->apr = $request->apr;
        $pool->vip_apr = $request->vip_apr;
        $pool->prize_pool = $request->prize_pool;

        if ($request->hasFile('image')) {
            $path = getFilePath('currency');
            $size = getFileSize('currency');
            try {
                $filename = fileUploader($request->image, $path, $size, @$pool->image);
                $pool->image = $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $pool->save();

        return redirect()->route('admin.pool.index')->with('success', 'Pool created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Pool Details';
        $pool = Pool::findOrFail($id);
        return view('admin.pool.show', compact('pageTitle', 'pool'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Staking Pool';
        $pool = Pool::findOrFail($id);
        return view('admin.pool.edit', compact('pageTitle', 'pool'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'apr' => 'required|numeric|min:0|max:1000',
            'vip_apr' => 'required|numeric|min:0|max:1000',
            'prize_pool' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $pool = Pool::findOrFail($id);
        $pool->name = $request->name;
        $pool->symbol = $request->symbol;
        $pool->apr = $request->apr;
        $pool->vip_apr = $request->vip_apr;
        $pool->prize_pool = $request->prize_pool;

        if ($request->hasFile('image')) {
            $path = getFilePath('currency');
            $size = getFileSize('currency');
            try {
                $filename = fileUploader($request->image, $path, $size, @$pool->image);
                $pool->image = $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $pool->save();

        return redirect()->route('admin.pool.index')->with('success', 'Pool updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function del(Request $request)
    {
        $pool = Pool::findOrFail($request->id);

        // Delete pool image if exists
        if ($pool->image && file_exists(public_path('images/pools/' . $pool->image))) {
            unlink(public_path('images/pools/' . $pool->image));
        }

        $pool->delete();

        return redirect()->route('admin.pool.index')->with('success', 'Pool deleted successfully');
    }


    public function userPools(Request $request){
        $pageTitle = 'User Pools';
        $coin_stacks=CoinStack::orderByDesc('created_at')->paginate(getPaginate());


        return view('admin.pool.user_pool', compact('pageTitle', 'coin_stacks'));
    }

}
