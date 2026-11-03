<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    protected $table = 'validations';
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['society_id', 'validator_id'];

    public function validator()
    {
        return $this->belongsTo(Validator::class, 'validator_id');
    }

}
