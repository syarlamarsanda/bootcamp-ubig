<?php

namespace App\Http\Controllers;

use App\Models\AvailableMonths;
use App\Models\InstallmentApplySociety;
use App\Models\InstallmentApplyStatus;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        $society = $request->user();
        $validation = Validation::where('society_id', $society->id)
            ->where('status', 'accepted')
            ->first();

        if (!$validation) {
            return response()->json([
                'message' => 'Your data validator must be accepted by validator before'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'instalment_id' => 'required',
            'months' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 401);
        }

        $sudahApply = InstallmentApplySociety::where('society_id', $society->id)->first();

        if ($sudahApply) {
            return response()->json([
                'message' => 'Application for a instalment can only be once'
            ], 401);
        }

        $month = AvailableMonths::where('installment_id', $request->instalment_id)
            ->where('month', $request->months)
            ->first();

        if (!$month) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => ['months' => ['The selected months is invalid.']]
            ], 401);
        }

        $apply = InstallmentApplySociety::create([
            'notes' => $request->notes,
            'available_month_id' => $month->id,
            'date' => now()->toDateString(),
            'society_id' => $society->id,
            'installment_id' => $request->instalment_id,
        ]);

        InstallmentApplyStatus::create([
            'date' => now()->toDateString(),
            'society_id' => $society->id,
            'installment_id' => $request->instalment_id,
            'available_month_id' => $month->id,
            'installment_apply_societies_id' => $apply->id,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Applying for Instalment successful'
        ], 200);
    }


    public function index(Request $request)
    {
        $applies = InstallmentApplySociety::with(['installment.brand', 'availableMonth', 'status'])
            ->where('society_id', $request->user()->id)
            ->get();

        $data = $applies->map(function ($apply) {
            return [
                'id' => $apply->installment->id,
                'car' => $apply->installment->cars,
                'brand' => $apply->installment->brand->brand,
                'price' => $apply->installment->price,
                'description' => $apply->installment->description,
                'applications' => [
                    [
                        'month' => $apply->availableMonth->month,
                        'nominal' => $apply->availableMonth->nominal,
                        'apply_status' => $apply->status->status,
                        'notes' => $apply->notes,
                    ]
                ],
            ];
        });

        return response()->json([
            'instalments' => $data
        ], 200);
    }
}
