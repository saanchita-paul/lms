<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicDnsRecord extends Model
{
    use HasFactory;

    protected $fillable = ['clinic_id', 'type', 'name', 'value'];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
