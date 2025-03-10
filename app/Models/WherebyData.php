<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WherebyData extends Model
{
    use HasFactory;


    protected $fillable = [
        'json_data',
        'clinic_id',
        'status'
    ];
}
