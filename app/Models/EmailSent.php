<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSent extends Model
{
    use HasFactory;
    public $table = 'email_sent';

    protected $fillable = [
        'user_id',
        'clinic_id',
        'from',
        'subject',
        'to',
        'bcc',
        'cc',
        'body',
        'body_excerpt',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function crmCustomer()
    {
        return $this->belongsTo(CrmCustomer::class);
    }

}
