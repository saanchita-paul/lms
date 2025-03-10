<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FbMessage extends Model
{
    use HasFactory;

    protected $fillable =[
        'fb_data',
        'lead_id',
        'converted'
    ];
}
