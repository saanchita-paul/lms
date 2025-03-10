<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FixedAppointmentAvailability extends Model
{
    use SoftDeletes;

    protected function getDateAttribute() {
        return Carbon::createFromFormat('Y-m-d', $this->attributes['date'])->format('m/d/Y');
    }

    public function appointment_availability()
    {
        return $this->belongs_to(AppointmentAvailability::class);
    }
}
