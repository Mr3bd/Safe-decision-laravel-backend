<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';


    protected $fillable = [
        'name_en',
        'name_ar',
    ];

    // Define a relationship with CarModel
    public function models(): HasMany
    {
        return $this->hasMany(CarModel::class, 'country_id');
    }
}
