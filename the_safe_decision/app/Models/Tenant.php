<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'national_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone_number',
        'whatsapp_number',
        'city_id',
        'region',
        'street',
        'building_number',
        'nearest_location',
        'driver_license',
        
    ];

    // // If you want to define a relationship with Rental history
    // public function rentals()
    // {
    //     return $this->hasMany(Rental::class); // Assuming you have a Rental model
    // }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
