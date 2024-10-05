<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleFeature extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'vehicle_features';

    // Fields that are mass assignable
    protected $fillable = [
        'name_en',
        'name_ar',
    ];
}
