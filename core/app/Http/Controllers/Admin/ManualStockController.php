<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use App\Models\ManualStock;
use App\Models\StockSellRequest;
use App\Models\User;
use App\Models\UserStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ManualStockController extends Controller
{
    public function index()
    {
        $data['stocks']=ManualStock::orderByDesc('created_at')->paginate(getPaginate());
        $data['pageTitle'] = "Stocks";
        return view('admin.manual_stock.list', $data);
    }

    public function create()
    {
        $data['pageTitle'] = "Manual Stock";
        return view('admin.manual_stock.create', $data);
    }


    public function store(Request $request)
    {

        $request->validate([
          'cusip_id'=>'required',
          'name'=>'required',
          'amount'=>'required',
          'holder'=>'required',
          'buy_date'=>'required',
          'status'=>'required',
          'buyer_email'=>'required',
          'buyer_number'=>'required',
          'broker'=>'required',
        ]);

        $length = strlen($request->cusip_id);

        if($length && $length > 8){
            return returnBack('Enter CUSIP-ID less than 8 charecter', 'error');
        }


        unset($request['_token']);

        $manual_stock= new ManualStock();
        $manual_stock->cusip_id=$request->cusip_id;
        $manual_stock->name=$request->name;
        $manual_stock->amount=$request->amount;
        $manual_stock->holder=$request->holder;
        $manual_stock->buy_date=$request->buy_date;
        $manual_stock->sell_date=$request->sell_date;
        $manual_stock->transfer_date=$request->transfer_date;
        $manual_stock->status=$request->status;
        $manual_stock->buyer_email=$request->buyer_email;
        $manual_stock->buyer_number=$request->buyer_number;
        $manual_stock->broker=$request->broker;
        $manual_stock->description=$request->description;
        $manual_stock->save();


        return returnBack('Manual Stock Successfully Created', 'success');
    }


    public function edit(ManualStock $manual_stock)
    {
        $data['pageTitle'] = "Manual Stock";
        $data['manual_stock']=$manual_stock;
        return view('admin.manual_stock.edit', $data);
    }



    public function update(ManualStock $manual_stock,Request $request)
    {

        $request->validate([
            'cusip_id'=>'required',
            'name'=>'required',
            'amount'=>'required',
            'holder'=>'required',
            'buy_date'=>'required',
            'status'=>'required',
            'broker'=>'required',
            'buyer_email'=>'required',
            'buyer_number'=>'required',
        ]);

        $length = strlen($request->cusip_id);

        if($length && $length > 8){
            return returnBack('Enter CUSIP-ID less than 8 charecter', 'error');
        }

        unset($request['_token']);

        $manual_stock->cusip_id=$request->cusip_id;
        $manual_stock->name=$request->name;
        $manual_stock->amount=$request->amount;
        $manual_stock->holder=$request->holder;
        $manual_stock->buy_date=$request->buy_date;
        $manual_stock->sell_date=$request->sell_date;
        $manual_stock->status=$request->status;
        $manual_stock->buyer_email=$request->buyer_email;
        $manual_stock->buyer_number=$request->buyer_number;
        $manual_stock->broker=$request->broker;
        $manual_stock->description=$request->description;
        $manual_stock->save();


        return returnBack('Manual Stock Successfully Updated', 'success');
    }



    public function destroy(ManualStock $manual_stock)
    {
        $manual_stock->delete();

        return returnBack('Manual Stock Successfully Deleted', 'success');

    }

    public function detailsStock(Request $request)
    {

        if(!$request->cusip_id){
            return response()->json(['status'=>'failed', 'data'=>[],'message'=>'Invalid CUSIP-ID']);
        }

        $length = strlen($request->cusip_id);


        $generateData=[];

        if($length && $length <= 9){
            $manual_stock=ManualStock::where('cusip_id', $request->cusip_id)->first();
            if(!$manual_stock){
                return response()->json(['status'=>'failed', 'data'=>[], 'message'=>'Invalid Stock']);
            }


            return response()->json(['status'=>'success', 'type'=>'manual', 'data'=>json_encode($manual_stock)]);


        }else{
            $manual_stock=UserStock::where('certificate_id', $request->cusip_id)->first();
            if(!$manual_stock){
                return response()->json(['status'=>'failed', 'data'=>[], 'message'=>'Invalid Stock']);
            }

            $gen_array['name']=$manual_stock->product->name;
            $gen_array['holder']=$manual_stock->user->fullname;
            $gen_array['amount']=$manual_stock->invest_amount;
            $gen_array['stock_type']=$manual_stock->type;
            $brokers=Broker::where('status', 'active')->pluck('name');

            $gen_array['brokers']=$brokers;
            $gen_array['buy_date']=$manual_stock->created_at->format('Y-m-d');

            if($manual_stock->status=='sell'){
                $gen_array['sell_date']=$manual_stock->updated_at->format('Y-m-d');
            }


            if($manual_stock->status=='exchange'){
                $gen_array['transfer_date']=$manual_stock->updated_at->format('Y-m-d');
            }

            if($manual_stock->type=='fix'){
                $gen_array['status']='Mutual Fund';
            }else{
                $gen_array['status']='Live Market';
            }
            $gen_array['buyer_email']=$manual_stock->user->email;
            $gen_array['stock_status']=$manual_stock->status;
            $gen_array['buyer_number']=$manual_stock->user->mobile;
            $gen_array['id']=$manual_stock->id;



            $gen_array['description']="This stock was purchased from ".$gen_array['name'].". The holder of the stock ".$gen_array['name']." is
            recognized as the owner of ".$gen_array['amount']." USD in terms of the basic share price of the company. As long as the stock is purchased,
            the owner will be considered a claimant. The stock price will be considered based on the market price of the company.
             This stock was purchased from ".$gen_array['broker']." with its support. The stock is currently at ".strtoupper($manual_stock->status);

            $gen_array=(object)$gen_array;



            return response()->json(['status'=>'success','type'=>'stock', 'data'=>json_encode($gen_array)]);

        }

    }


    public function verifyLogin(Request $request)
    {
        if(!$request->email || !$request->password){
            return response()->json(['status'=>'failed', 'data'=>[],'message'=>'Invalid Credentials']);
        }

        $user=User::where('status', '1')->where('email', $request->email)->orWhere('username', $request->email)->first();
        if(!$user){
            return response()->json(['status'=>'failed', 'data'=>[],'message'=>'Invalid Credentials']);
        }

        $credentials['email']=$user->email;
        $credentials['password']=$request->password;

        $remember_me = $request->has('remember_me') ? true : false;
        if (Auth::guard()->attempt($credentials, $remember_me)) {


            return response()->json(['status'=>'success', 'type'=>'login']);
        }


        return response()->json(['status'=>'failed', 'data'=>[],'message'=>'Invalid Credentials']);

    }


    public function sellRequest(Request $request){



       try{
           $validator = Validator::make($request->all(), [
               'login_email' => 'required',
               'login_password' => 'required',
               'stock_holder_full_name' => 'required',
               'mothers_name' => 'required',
               'phone_number' => 'required',
               'marital_status' => 'required',
               'home_ownership_status' => 'required',
               'id_verification' => 'required',
               'emergency_contact' => 'required',
               'relationship' => 'required',
               'relation_full_name' => 'required',
               'relation_phone_number' => 'required',
               'relation_address' => 'required',
               'purchase_date' => 'required',
               'stock_amount' => 'required',
               'broker_certificate' => 'required',
               'stock_id' => 'required',
               'doc_number' => 'required',
           ]);
           if ($validator->fails()) {
               return response()->json(['message' => $validator->errors()->messages()], 404);
           }



           if(!$request->login_email || !$request->login_password){
               return response()->json(['status'=>'failed', 'data'=>[],'message'=>'Invalid Credentials']);
           }

           $user=User::where('status', '1')->where('email', $request->login_email)->orWhere('username', $request->login_email)->first();
           if(!$user){
               return response()->json(['status'=>'failed', 'data'=>[],'message'=>'Invalid Credentials']);
           }

           if($user->kyc_data){
               $kycData=$user->kyc_data?json_decode($user->kyc_data):[];

               if(isset($kycData->id_number) && $kycData->id_number != $request->doc_number){
                   return response()->json(['status'=>'failed', 'data'=>[],'message'=>'Document Data Not Valid']);
               }

               if(isset($kycData->emergency_phone) && $kycData->emergency_phone != $request->emergency_contact){
                   return response()->json(['status'=>'failed', 'data'=>[],'message'=>'Emergency Data Not Valid']);
               }

           }else{
               return response()->json(['status'=>'failed', 'data'=>[],'message'=>'Document Data Not Valid']);
           }

           $mobileNo=$user->dial_code.$user->mobile;
           if($mobileNo != $request->phone_number){
               return response()->json(['status'=>'failed', 'data'=>[],'message'=>'Mobile Number Not Valid']);
           }

           $credentials['email']=$user->login_email;
           $credentials['password']=$request->login_password;

           $remember_me = $request->has('remember_me') ? true : false;
           if (!Auth::guard()->attempt($credentials, $remember_me)) {
               return response()->json(['status'=>'failed', 'type'=>'login']);
           }




           $userStock = UserStock::where('user_id', $user->id)->where('id', $request->stock_id)->first();
           if (!$userStock) {
               $message = 'Please to sell a valid stock';
               return response()->json(['status'=>'failed', 'data'=>[],'message'=> $message]);
           }
           $preRequest = StockSellRequest::where('user_id', $user->id)->where('user_stock_id', $userStock->id)->first();
           if ($preRequest && $preRequest->status='pending') {
               $errmessage = 'You already created a sell request for this stock. Wait for update';
               return response()->json(['status'=>'failed', 'data'=>[],'message'=> $errmessage]);
           }

           if ($preRequest && $preRequest->status=='approved') {
               $errmessage = 'You already sold this stock';
               return response()->json(['status'=>'failed', 'data'=>[],'message'=> $errmessage]);
           }

           $purchaseDate=$userStock->created_at->format('Y-m-d');
           if($purchaseDate != $request->purchase_date){
               return response()->json(['status'=>'failed', 'data'=>[],'message'=> 'Data not valid']);
           }

           $sellRequest = new StockSellRequest();
           $sellRequest->user_id = $userStock->user_id;
           $sellRequest->product_id = $userStock->product_id;
           $sellRequest->user_stock_id = $userStock->id;
           $sellRequest->type = $userStock->type;
           $sellRequest->others = json_encode($request->all());
           $sellRequest->save();


           return response()->json(['status'=>'success', 'data'=>[],'message'=>'Sell request created successfully']);
       }catch (\Exception $exception){
           return response()->json(['status'=>'failed', 'data'=>[],'message'=>$exception->getMessage()]);
       }


    }

}
