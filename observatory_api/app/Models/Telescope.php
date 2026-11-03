<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telescope extends Model
{
    protected $table = 'telescopes';
    public $timestamps = false;
    protected $guarded = ['id'];
}
