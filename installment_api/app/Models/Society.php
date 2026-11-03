<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Society extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'societies';
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = ['password','login_tokens'];

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }
}
