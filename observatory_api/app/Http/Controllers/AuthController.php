<?php

namespace App\Http\Controllers;

use App\Models\Society;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $society = Society::with('regional')
        ->where('username', $request->username)
        ->where('password', $request->password)
        ->first();

        if(!$society)
            {
                return response()->json([
                    'message'=>'Username or Password incorrect'
                ],401);
            }

        $token = $society->createToken('token')->plainTextToken;
        
        return response()->json([
            'name'=>$society->name,
            'username'=>$society->username,
            'member_type'=>$society->member_type,
            'born_date'=>$society->born_date,
            'gender'=>$society->gender,
            'address'=>$society->address,
            'token'=>$token,
            'regional'=>[
                'id'=>$society->regional->id,
                'province'=>$society->regional->province,
                'district'=>$society->regional->district,
            ],
        ],200);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message'=>'Logout success'
        ],200);
    }
}
