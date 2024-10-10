<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RentalContract extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'car_rent_contracts';

    // Specify the primary key if it's not 'id'
    // protected $primaryKey = 'id';

    // Disable auto-incrementing if using a custom ID generation
    // public $incrementing = false;

    // Specify the data types for the attributes if necessary
    // protected $keyType = 'string';

    // Define the fillable attributes
    protected $fillable = [
        'institution_id',
        'tenant_id',
        'rent_date',
        'return_date',
        'car_id',
        'status_id',
        'km_reading_before',
        'front_image',
        'rear_image',
        'right_side',
        'left_side',
        'fuel_before_reading',
        'fuel_after_reading',
        'price'
    ];

    // Define the relationships if necessary
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function car()
    {
        return $this->belongsTo(InstitutionCar::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function status()
    {
        return $this->belongsTo(CarContractStatus::class);
    }

    public function featuresBefore()
    {
        return $this->belongsToMany(VehicleFeature::class, 'car_contract_before_vfeatures', 'contract_id', 'feature_id');
    }

    public function featuresAfter()
    {
        return $this->belongsToMany(VehicleFeature::class, 'car_contract_after_vfeatures', 'contract_id', 'feature_id');
    }
}
