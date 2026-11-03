<?php

namespace App\Http\Controllers;

use App\Models\Validation;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'institution'=>'required',
            'occupation'=>'required',
            'reason_accepted'=>'required',
        ]);

        Validation::create([
            'society_id'=>$request->user()->id,
            'institution'=>$request->institution,
            'occupation'=>$request->occupation,
            'reason_accepted'=>$request->reason_accepted,
        ]);

        return response()->json([
            'message'=>'Request data validation sent successful'
        ], 200);
    }

    public function index(Request $request)
    {
        $validation = Validation::with('validator')
        ->where('society_id',$request->user()->id)
        ->orderBy('id','desc')
        ->first();

        return response()->json(['validation'=>$validation],200);
    }
}
