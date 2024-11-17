<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Weather extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'current_weather_data';

    public function is_expired()
    {
        return; 
    }
}
