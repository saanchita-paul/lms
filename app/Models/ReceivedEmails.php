<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivedEmails extends Model
{
    use HasFactory;

    protected $fillable = ['clinic_id','email_token','email_created_date','email_read'];
}
