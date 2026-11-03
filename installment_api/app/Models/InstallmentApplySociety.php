<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentApplySociety extends Model
{
    protected $table = 'installment_apply_societies';
    public $timestamps = false;
    protected $guarded = [];

    public function installment()
    {
        return $this->belongsTo(Installment::class, 'installment_id');
    }

    public function availableMonth()
    {
        return $this->belongsTo(AvailableMonths::class, 'available_month_id');
    }

    public function status()
    {
        return $this->hasOne(InstallmentApplyStatus::class, 'installment_apply_societies_id');
    }
}
