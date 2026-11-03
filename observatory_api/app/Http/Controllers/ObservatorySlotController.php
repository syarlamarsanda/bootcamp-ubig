<?php

namespace App\Http\Controllers;

use App\Models\ObservatorySlot;
use Illuminate\Http\Request;

class ObservatorySlotController extends Controller
{
    public function index(Request $request)
    {
        $slots = ObservatorySlot::with('telescope:id,name,type')
        ->when($request->search, function($query, $search){
            $query->where('event_name','like',"%{$search}%");
        })
        ->when($request->min_price, function ($query, $min) {
            $query->where('price_per_slot','>=',$min);
        })
        ->when($request->max_price, function ($query, $max) {
            $query->where('price_per_slot','<=',$max);
        })
        ->when($request->type, function($query, $type){
            $query->whereHas('telescope', function($q) use ($type){
                $q->where('type',$type);
            });
        })
        ->get();

        return response()->json(['slots'=>$slots],200);
    }

    public function show($id)
    {
        $slot = ObservatorySlot::with('telescope:id,name,type')->find($id);

        return response()->json(['slot'=>$slot],200);

    }
}
