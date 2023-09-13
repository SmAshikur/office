<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostType extends Model
{
    use HasFactory;
    public function country()
    {
        return $this->belongsTo(\App\Country::class, 'country_id');
    }
}
