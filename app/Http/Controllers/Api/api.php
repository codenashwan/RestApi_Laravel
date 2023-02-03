<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\contacts;
class api extends Controller {
    
    public function home(){
        return "Hello wrold";
    }

    public function contact(Request $request){
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|min:3',
            'email' => 'required|email',
            'phonenumber' => 'required|min:11',
            'message' => 'required|min:3',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()->all()], 401);
        }else{
            contacts::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phonenumber' => $request->phonenumber,
                'message' => $request->message,
            ]);
            return response()->json(['success' => 'Message sent successfully'], 200);
        }

    }


}
