<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Society extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'societies';
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['password', 'login_tokens'];

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'regional_id');
    }
}
