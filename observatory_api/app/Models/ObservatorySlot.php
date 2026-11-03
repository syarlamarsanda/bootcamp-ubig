<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObservatorySlot extends Model
{
    protected $table = 'observatory_slots';
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['telescope_id'];

    public function telescope()
    {
        return $this->belongsTo(Telescope::class, 'telescope_id');
    }
}
