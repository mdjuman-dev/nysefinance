<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockVideo;
use Illuminate\Http\Request;

class StockVideoController extends Controller
{
    public function index()
    {

        $data['pageTitle'] = "Stock Video";
        $data['videos']=StockVideo::orderByDesc('created_at')->paginate(getPaginate());

        return view('admin.video.list', $data);
    }

    public function create()
    {

        $data['pageTitle'] = "Stock Video Create";
        $data['products']=Product::orderByDesc('created_at')->get();

        return view('admin.video.create', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id'=>'required',
            'video'=>'required'
        ]);

        $preVideo=StockVideo::where('product_id', $request->product_id)->count();
        if($preVideo && $preVideo >= 4){
            return returnBack('Can\'t add more than 4 video for one product/stock', 'success');
        }


        $video= new StockVideo();
        $video->product_id=$request->product_id;

        if ($request->hasFile('video')) {
            $path = getFilePath('currency');
            $size = getFileSize('currency');
            try {
                $filename = fileUploader($request->video, $path, $size,null);
                $video->video = $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your video'];
                return back()->withNotify($notify);
            }
        }
        $video->save();


        return returnBack('Stock Video Successfully Added', 'success');

    }

    public function edit(StockVideo $stock_video)
    {

        $data['pageTitle'] = "Stock Video Edit";
        $data['products']=Product::orderByDesc('created_at')->get();
        $data['stock_video']=$stock_video;

        return view('admin.video.edit', $data);
    }

    public function update(Request $request, StockVideo $stock_video)
    {
        $request->validate([
            'product_id'=>'required',
        ]);


        $stock_video->product_id=$request->product_id;

        $old_video=$stock_video->video;
        if ($request->hasFile('video')) {
            $path = getFilePath('currency');
            $size = getFileSize('currency');
            try {

                $filename = fileUploader($request->video, $path, $size,null);
                $stock_video->video = $filename;

                $old_file=getFilePath('currency') .'/'.$old_video;

                if (file_exists($old_file)) {
                    unlink($old_file);
                }

            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your video'];
                return back()->withNotify($notify);
            }
        }


        $stock_video->save();


        return returnBack('Stock Video Successfully Updated', 'success');

    }

}
