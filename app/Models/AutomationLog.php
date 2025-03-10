<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id', 'lead_id', 'status_id', 'dayinterval', 'type',
    ];

    /**
     * Get the clinic that owns the automation log.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the lead that owns the automation log.
     */
    public function lead()
    {
        return $this->belongsTo(CrmCustomer::class);
    }

    /**
     * Get the status that owns the automation log.
     */
    public function status()
    {
        return $this->belongsTo(CrmStatus::class);
    }
}
