<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    protected $table = "validations";
    public $timestamps = false;
    protected $guarded = [];

    public function Validator()
    {
        return $this->belongsTo(Validator::class, 'validator_id');
    }
}
