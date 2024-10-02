<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'password',
        'institution_id',
        'role_id',
        'status_id',
        'otp',
    ];

    /**
     * Get the institution associated with the pending user.
     */
    public function institution()
    {
        return $this->belongsTo(PendingInstitution::class, 'institution_id');
    }
}