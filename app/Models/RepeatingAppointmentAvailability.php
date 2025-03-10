<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepeatingAppointmentAvailability extends Model
{
    use SoftDeletes;

    public function appointment_availability()
    {
        return $this->belongs_to(AppointmentAvailability::class);
    }
}
