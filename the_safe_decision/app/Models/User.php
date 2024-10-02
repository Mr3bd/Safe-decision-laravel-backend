<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


// for sanctum
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // [HasApiTokens] for sanctum
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
     protected $fillable = [
        'name',
        'phone_number',
        'email',
        'institution_id',
        'role_id',
        'status_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function role()
    {
        return $this->belongsTo(UserRole::class);
    }

    public function status()
    {
        return $this->belongsTo(UserStatus::class);
    }


}
