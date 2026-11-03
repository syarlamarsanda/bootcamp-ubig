<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = "reservations";
    public $timestamps = false;
    protected $guarded = ['id'];

    public function slot()
    {
        return $this->belongsTo(ObservatorySlot::class, 'observatory_slot_id');
    }
}
