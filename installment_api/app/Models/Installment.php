<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    protected $table = 'installment';
    public $timestamps = false;
    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }
    public function availableMonths()
    {
        return $this->hasMany(AvailableMonths::class, 'installment_id');
    }
}
