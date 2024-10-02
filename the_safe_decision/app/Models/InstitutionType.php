<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionType extends Model
{
    protected $table = 'institution_types';

    // You can specify fillable fields if needed
    protected $fillable = ['name', 'nameEn'];
}
