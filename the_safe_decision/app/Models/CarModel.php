<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'manufacturer_id',
    ];

    // Define a relationship with CarManufacture
    public function manufacture(): BelongsTo
    {
        return $this->belongsTo(CarManufacture::class, 'manufacturer_id');
    }
}
