<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;
use Illuminate\Notifications\Notifiable;

class CrmChat extends Model
{
    use SoftDeletes, Auditable, HasFactory, Notifiable;

    public $table = 'crm_chats';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'lead_id',
        'user_id',
        'inbound',
        'platform',
        'chat',
        'from',
        'to',
        'delivered',
        'read',
        'is_sms',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function customer()
    {
        return $this->belongsTo(CrmCustomer::class, 'lead_id');
    }
    
    public function markRead()
    {
        if ($this->read == 0) {
            $this->update(['read' => 1]);
        }
    }
    
    public function isDelivered()
    {
        return $this->update(['delivered' => 1]);
    }

    public function routeNotificationForSlack($notification)
    {
        return env('SLACK_NOTIFICATION_WEBHOOK');
    }
}
