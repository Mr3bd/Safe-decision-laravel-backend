<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingInstitution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'institution_number',
        'institution_type_id'
    ];

    /**
     * Get the pending users for the institution.
     */
    public function users()
    {
        return $this->hasMany(PendingUser::class, 'institution_id');
    }
}