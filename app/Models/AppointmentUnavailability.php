<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentUnavailability extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function appointment_availability(){
        return $this->belongsTo(AppointmentAvailability::class);
    }
}
