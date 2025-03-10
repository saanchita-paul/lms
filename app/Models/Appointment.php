<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function appointment_availability()
    {
        return $this->belongsTo(AppointmentAvailability::class);
    }

    public function crm_customer()
    {
        return $this->belongsTo(CrmCustomer::class);
    }

    public function services()
    {
        return $this->belongsTo(Services::class, 'service_type');
    }    
}
