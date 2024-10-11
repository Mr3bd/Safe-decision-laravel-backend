<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'institution_number',
        'institution_type_id',
        'emergency_number',
        'address_ar',
        'address_en',
        'logo_image',
        'balance',
        'isActive'
    ];

    /**
     * Get the users for the institution.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function institutionType()
    {
        return $this->belongsTo(InstitutionType::class);
    }

    public function institutionCars()
    {
        return $this->hasMany(InstitutionCar::class);
    }

}
