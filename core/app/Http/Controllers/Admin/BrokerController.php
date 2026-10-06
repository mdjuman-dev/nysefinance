<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use Illuminate\Http\Request;

class BrokerController extends Controller
{

    public function index()
    {

        $data['brokers']=Broker::orderByDesc('created_at')->paginate(getPaginate());
        $data['pageTitle'] = "Brokers";
        return view('admin.broker.list', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'type'=>'required',
            'status'=>'required'
        ]);

        $broker=new Broker();
        $broker->name=$request->name;
        $broker->type=$request->type;
        $broker->status=$request->status;
        $broker->save();

        return returnBack('Broker Successfully Created', 'success');
    }

    public function create()
    {

        $data['pageTitle'] = "Broker Create";
        return view('admin.broker.create', $data);

    }

    public function edit(Broker $broker)
    {

        $data['pageTitle'] = "Brokers";
        $data['broker'] = $broker;
        return view('admin.broker.edit', $data);

    }


    public function update(Broker $broker,Request $request)
    {
        $request->validate([
            'name'=>'required',
            'type'=>'required',
            'status'=>'required'
        ]);

        $broker->name=$request->name;
        $broker->type=$request->type;
        $broker->status=$request->status;
        $broker->save();

        return returnBack('Broker Successfully Updated', 'success');
    }

    public function destroy(Broker $broker)
    {
        $broker->delete();

        return returnBack('Broker Successfully Deleted', 'success');
    }


}
