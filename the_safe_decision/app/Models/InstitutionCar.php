<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'model_id',
        'tagNumber',
        'manu_year'
    ];

    // Define a relationship with CarModel
    public function model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'model_id');
    }

    // Define a relationship with Institution (assuming you have an Institution model)
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function rentalContracts()
    {
        return $this->hasMany(RentalContract::class, 'car_id');
    }
}
