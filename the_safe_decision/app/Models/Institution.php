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
        'institution_type_id'
    ];

    /**
     * Get the users for the institution.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

}
