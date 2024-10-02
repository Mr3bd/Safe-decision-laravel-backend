<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{

    use HasFactory;

    // Specify the table name if it's not plural
    protected $table = 'user_roles';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'id';

    // Define the fields that can be mass-assigned
    protected $fillable = [
        'name',
    ];

    // Define relationships if any
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
