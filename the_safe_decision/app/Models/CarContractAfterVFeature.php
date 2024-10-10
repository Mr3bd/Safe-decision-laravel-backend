<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarContractAfterVFeature extends Model
{
    // Define the table name explicitly if it does not follow Laravel's naming convention
    protected $table = 'car_contract_after_vfeatures';

    // Define the fillable properties for mass assignment
    protected $fillable = [
        'feature_id',
        'contract_id',
    ];
}
