<?php

namespace App\Http\Controllers;

use App\Models\AvailableMonth;
use App\Models\Installment;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    private function format($car)
    {
        return[
            'id'=>$car->id,
            'car'=>$car->cars,
            'brand'=>$car->brand->brand,
            'price'=>$car->price,
            'description'=>$car->description,
            'available_month'=>$car->availableMonths->map(function($month) {
                return [
                    'month'=>$month->month,
                    'description'=>$month->description
                ];
            }),
        ];
    }

    public function index(Request $request)
    {
        $cars = Installment::with(['brand','availableMonths'])->get();

        return response()->json([
            'cars'=>$cars->map(fn ($car) => $this->format($car)),   
        ], 200);
    }

    public function show($id)
    {
        $car = Installment::with(['brand','availableMonths'])->find($id);
        

        if(!$car){
            return response()->json(['message'=>'Installment not found'], 404);
        }

        return response()->json([
            'instalment'=>$this->format($car),
        ],200);
    }
}
