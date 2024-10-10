<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarContractStatus extends Model
{
    use HasFactory;

    // Specify the table name if it's not plural
    protected $table = 'car_contract_statuses';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Define relationships if any
    public function contracts()
    {
        return $this->hasMany(RentalContract::class);
    }
}
