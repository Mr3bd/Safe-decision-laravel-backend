<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarManufacture extends Model
{
    use HasFactory;

    protected $table = 'car_manufacturers';


    protected $fillable = [
        'name_en',
        'name_ar',
    ];

    // Define a relationship with CarModel
    public function models(): HasMany
    {
        return $this->hasMany(CarModel::class, 'manufacturer_id');
    }
}
