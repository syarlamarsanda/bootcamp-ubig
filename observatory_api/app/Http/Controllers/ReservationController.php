<?php

namespace App\Http\Controllers;

use App\Models\ObservatorySlot;
use App\Models\Reservation;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $society =  $request->user();

        $accepted = Validation::where('society_id',$society->id)
            ->where('status','accepted')
            ->exists();

        if(!$accepted)
            {
                return response()->json(['message'=>'Your data validation must be accepted by validator before'],401);
            }
        
        $validator = Validator::make($request->all(), [
            'observatory_slot_id'=>'required|exists:observatory_slots,id',
            'requested_tickets'=>'required|integer|min:1',
        ]);
        if($validator->fails()){
            return response()->json([
                'message'=>'Invalid field',
                'errors'=>$validator->errors()
            ],401);
        }
        $sudahBooking = Reservation::where('society_id', $society->id)
           ->where('observatory_slot_id', $request->observatory_slot_id)
           ->where('status', '!=', 'cancelled')
           ->exists();

        if ($sudahBooking) {
        return response()->json([
            'message' => 'Application for a reservation can only be once'
        ], 401);
        }
        DB::beginTransaction();

        $slot = ObservatorySlot::lockForUpdate()->find($request->observatory_slot_id);

        if ($slot->booked_count + $request->requested_tickets > $slot->max_capacity) {
            DB::rollBack();
            return response()->json([
                'message' => 'Requested ticket quantity exceeds available slot capacity'
            ], 422);
        }
        Reservation::create([
            'society_id' => $society->id,
            'observatory_slot_id' => $slot->id,
            'booking_code' => 'OBS-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'total_tickets' => $request->requested_tickets,
            'total_price' => $slot->price_per_slot * $request->requested_tickets,
            'notes' => $request->notes,
        ]);
        $slot->increment('booked_count', $request->requested_tickets);

        DB::commit();

        return response()->json([
            'message' => 'Applying for reservation successful'
        ], 200);
            
    }
    public function reset(Request $request)
    {
        $reservations = Reservation::where('society_id', $request->user()->id)->get();

        foreach ($reservations as $reservation) {
            ObservatorySlot::where('id', $reservation->observatory_slot_id)
                ->decrement('booked_count', $reservation->total_tickets);

            $reservation->delete();
        }

        return response()->json([
            'message' => 'Reset success'
        ], 200);
    }
    public function index(Request $request)
    {
        $reservations = Reservation::with('slot.telescope')
            ->where('society_id', $request->user()->id)
            ->get();

        $data = $reservations->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'booking_code' => $reservation->booking_code,
                'total_tickets' => $reservation->total_tickets,
                'total_price' => $reservation->total_price,
                'status' => $reservation->status,
                'notes' => $reservation->notes,
                'slot' => [
                    'id' => $reservation->slot->id,
                    'event_name' => $reservation->slot->event_name,
                    'event_date' => $reservation->slot->event_date,
                    'telescope' => $reservation->slot->telescope->name,
                ],
            ];
        });

        return response()->json([
            'reservations' => $data
        ], 200);
    }
}
