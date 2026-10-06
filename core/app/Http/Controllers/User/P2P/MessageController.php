<?php

namespace App\Http\Controllers\User\P2P;

use App\Constants\Status;
use App\Events\P2PMessage;
use App\Http\Controllers\Controller;
use App\Models\P2P\Trade;
use App\Models\P2P\TradeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{

    public function save(Request $request, $tradeId)
    {

        if($request->hasFile('attach_file') && !$request->message){
            $request['message']='Attach File';
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return jsonResponse($validator->errors()->all());
        }

        $trade  = Trade::myTrade()->where('id', $tradeId)->first();

        if (!$trade) {
            return jsonResponse("Trade not found");
        }
        if ($trade->status == Status::P2P_TRADE_COMPLETED || $trade->status == Status::P2P_TRADE_CANCELED) {
            return jsonResponse("Trade is completed");
        }

        $attachFile=null;
        if ($request->hasFile('attach_file')) {
            $file = $request->file('attach_file');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
            $attachFile = $imageName;
        }


        $userId             = auth()->id();
        $message            = new TradeMessage();
        $message->trade_id  = $trade->id;
        $message->sender_id = $userId;

        if ($trade->seller_id == $userId) {
            $receiverId = $trade->buyer_id;
        } else {
            $receiverId = $trade->seller_id;
        }

        $message->receiver_id = $receiverId;
        $message->message     = $request->message;
        if($attachFile){
            $message->attachment =$attachFile;
        }
        $message->save();

        $senderHtml   = view('Template::user.p2p.trade.single_message', ['message' => $message, 'direction' => 'sender'])->render();
        $receiverHtml = view('Template::user.p2p.trade.single_message', ['message' => $message, 'direction' => 'receiver'])->render();
        $adminHtml    = view("admin.p2p.trade.single_message", ['message' => $message,])->render();

//        event(new P2PMessage($trade->id, $message->receiver_id, $receiverHtml, $adminHtml));

        return jsonResponse(null, true, ['html' => $senderHtml]);
    }

    public function downloadChatAttachFile(Request $request)
    {
        if(!$request->f){
            $notifyIn[] = ['error', 'Invalid File'];
            return redirect()->back()->withNotify($notifyIn);
        }
        $file=public_path('uploads/'.$request->f);

        if(file_exists($file)){
            return response()->download($file);
        }

        return abort('404');

    }

    public function liveMessage($id)
    {
        $trade     = Trade::myTrade()->where('id', $id)->with("paymentMethod")->first();

        if(!$trade){
            return response()->json(['status'=>'failed','data'=>[]]);
        }
        $messages = TradeMessage::where('trade_id', $trade->id)->get();
        $user      = auth()->user();

        // the Vue trade page polls for plain data instead of rendered HTML
        if (request('format') === 'json') {
            return response()->json(['status' => 'success', 'messages' => app(TradeController::class)->messagePayload($messages, $user->id), 'tradeStatus' => (int) $trade->status]);
        }

        // Render the Blade view dynamically
        $html = view('Template::user.p2p.trade.live_message', compact('messages', 'user'))->render();

        return response()->json([
            'status' => 'success',
            'data' => $html
        ]);
    }
}
