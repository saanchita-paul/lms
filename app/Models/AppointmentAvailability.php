<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentAvailability extends Model
{
    use SoftDeletes;

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function repeating_appointment_availability()
    {
        return $this->hasOne(RepeatingAppointmentAvailability::class);
    }

    public function fixed_appointment_availabilities()
    {
        return $this->hasMany(FixedAppointmentAvailability::class);
    }

    public function appointment_unavailabilities()
    {
        return $this->hasMany(AppointmentUnavailability::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function appointment_reminders()
    {
        return $this->hasMany(AppointmentReminder::class);
    }
}
