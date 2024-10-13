<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantCarRentReview extends Model
{
     protected $fillable = [
        'contract_id',
        'tenant_id',
        'appointments',
        'accidents',
        'violations',
        'financial',
        'cleanliness',
        'description',
        'national_id'
    ];

    // Define relationships
    public function contract()
    {
        return $this->belongsTo(CarRentContract::class, 'contract_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
