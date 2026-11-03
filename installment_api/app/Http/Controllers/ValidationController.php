<?php

namespace App\Http\Controllers;

use App\Models\Society;
use App\Models\Validation;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reason_accepted' => 'required',
        ]);

        $society = $request->user();

        if (Validation::where('society_id', $society->id)->exists())
            {
                return response()->json([
                    'message'=>'You have already requested data validation'
                ],401);
            }
            Validation::create([
                'society_id'=>$society->id,
                'job'=> $request->job,
                'job_description'=>$request->job_description,
                'income'=>$request->income,
                'reason_accepted' => $request->reason_accepted,
            ]);

            return response()->json([
                'message'=>'Request data validation sent succesful'
            ], 200);
    }

    public function index(Request $request)
    {
        $validation =  Validation::with('validator')
        ->where('society_id', $request->user()->id)
        ->first();

        return response()->json([
            'validation'=>$validation
        ], 200);

    }
}
