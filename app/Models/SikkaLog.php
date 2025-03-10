<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SikkaLog extends Model
{
    use HasFactory;
    public $table = 'sikka_log';
    protected $fillable = [
        'logs',
    ];
}
