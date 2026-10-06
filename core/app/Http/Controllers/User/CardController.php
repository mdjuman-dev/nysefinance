<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CardApplication;
use Illuminate\Http\Request;

class CardController extends Controller
{

    public function application()
    {

        $pageTitle = "Apply For Card";

        $cardApplication= CardApplication::where('user_id', auth()->user()->id)->first();

        if($cardApplication) {
            $notify[] = ['success', 'You already submitted a application, we will notify you soon.'];
            return redirect()->route('user.home')->withNotify($notify);
        }


        return view('Template::user.card.application', compact('pageTitle'));
    }

    public function storeApplication(Request $request)
    {

        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'phone_number'=>'required',
            'address'=>'required',
            'card_type'=>'required'
        ]);


        $user=auth()->user();


        $doc_number='xxx';
        $docFiles=[];

        if($request->doc_type=='nid'){

            if(!$request->nid_number || !$request->nid_number || !$request->nid_number){
                return redirect()->back()->withErrors(['errors'=>'invalid documents']);
            }

            $doc_number=$request->nid_number;
            if ($request->hasFile('nid_front')) {
                $path = getFilePath('currency');
                $size = getFileSize('currency');
                try {
                    $filename = fileUploader($request->nid_front, $path, $size,null);
                    $docFiles['nid_front'] = $filename;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your document'];
                    return back()->withNotify($notify);
                }
            }


            if ($request->hasFile('nid_back')) {
                $path = getFilePath('currency');
                $size = getFileSize('currency');
                try {
                    $filename = fileUploader($request->nid_back, $path, $size,null);
                    $docFiles['nid_back'] = $filename;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your document'];
                    return back()->withNotify($notify);
                }
            }
        }elseif($request->doc_type=='passport'){


            if(!$request->passport_number || !$request->passport){
                return redirect()->back()->withErrors(['errors'=>'invalid documents']);
            }

            $doc_number=$request->passport_number;
            if ($request->hasFile('passport')) {
                $path = getFilePath('currency');
                $size = getFileSize('currency');
                try {
                    $filename = fileUploader($request->passport, $path, $size,null);
                    $docFiles['passport'] = $filename;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your document'];
                    return back()->withNotify($notify);
                }
            }

        }elseif($request->doc_type=='license'){

            if(!$request->license || !$request->nid_number || !$request->nid_number){
                return redirect()->back()->withErrors(['errors'=>'invalid documents']);
            }

            $doc_number=$request->license;
            if ($request->hasFile('license_front')) {
                $path = getFilePath('currency');
                $size = getFileSize('currency');
                try {
                    $filename = fileUploader($request->license_front, $path, $size,null);
                    $docFiles['license_front'] = $filename;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your document'];
                    return back()->withNotify($notify);
                }
            }


            if ($request->hasFile('license_back')) {
                $path = getFilePath('currency');
                $size = getFileSize('currency');
                try {
                    $filename = fileUploader($request->license_back, $path, $size,null);
                    $docFiles['license_back'] = $filename;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your document'];
                    return back()->withNotify($notify);
                }
            }
        }else if($request->doc_type=='bank'){
            if(!$request->bank_statement){
                return redirect()->back()->withErrors(['errors'=>'invalid documents']);
            }

            $doc_number=$request->license;
            if ($request->hasFile('bank_statement')) {
                $path = getFilePath('currency');
                $size = getFileSize('currency');
                try {
                    $filename = fileUploader($request->bank_statement, $path, $size,null);
                    $docFiles['bank_statement'] = $filename;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload your document'];
                    return back()->withNotify($notify);
                }
            }

        }

        if($request->doc_type !='bank' && !$doc_number){
            return redirect()->back()->withErrors(['errors'=>'invalid doc type']);
        }

        $checkApp=CardApplication::where('email', $request->email)->orWhere('phone', $request->phone_number)->first();
        if($checkApp){
            $notify[] = ['error', 'This user information already exists.'];
            return back()->withNotify($notify);
        }

        $checkApp=CardApplication::where('doc_type', $request->doc_type)->orWhere('doc_number', $doc_number)->first();
        if($checkApp){
            $notify[] = ['error', 'This information already exists.'];
            return back()->withNotify($notify);
        }



        $application=new CardApplication();
        $application->user_id=$user->id;
        $application->name=$request->name;
        $application->email=$request->email;
        $application->phone=$request->phone_number;
        $application->address=$request->address;
        $application->card_type=strtolower($request->card_type);
        $application->doc_type=$request->doc_type;
        $application->doc_number=$request->name;
        $application->document=json_encode($docFiles);
        $application->save();

        return  redirect()->route('user.home')->withNotify(['success'=>'Your application has been submitted.']);

    }


}
