<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
//use Spatie\MediaLibrary\HasMedia\HasMedia;
//use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
//use Spatie\MediaLibrary\Models\Media;
use Illuminate\Notifications\Notifiable;


class Reports extends Model implements HasMedia
{
    use SoftDeletes;
    use Notifiable;
    use InteractsWithMedia;
    //use HasMediaTrait;
    use Auditable;
    use HasFactory;

    public const WON_LOST_SELECT = [
        'Won'  => 'Yes',
        'Lost' => 'No',
    ];

    public const PHONE_FORM_SELECT = [
        'Web Form'   => 'Web Form',
        'Phone Call' => 'Phone Call',
    ];

    public const LEAD_TYPE_SELECT = [
        'Call Center Lead' => 'Microsite Lead',
        'In-Office Lead'   => 'Office Lead',
    ];

    public const THREE_PLUS_ATTEMPTS_SELECT = [
        '4'     => '4',
        '5'     => '5',
        '6'     => '6',
        '7'     => '7',
        'other' => 'other',
    ];

    public const DEAL_STATUS_SELECT = [
        'Needs Financing or Gathering Money' => 'Needs Financing or Gathering Money',
        'Talk to Spouse, Partner or Family Member'   => 'Talk to Spouse, Partner or Family Member',
        'Wants to Compare Prices'            => 'Wants to Compare Prices',
        'Other' => 'Other',
    ];

    public const REASON_SELECT = [
        'Price shopping'                    => 'Price shopping',
        'Office is too far'                 => 'Office is too far',
        'Medicaid or Medicare patient'      => 'Medicaid or Medicare patient',
        'Too expensive/couldn\'t afford it' => 'Too expensive/couldn\'t afford it',
        'Call disconnected, hung up'        => 'Call disconnected, hung up',
    ];

    public $table = 'crm_customers';

    public static $searchable = [
        'clinic_id',
        'first_name',
        'last_name',
        'phone_form',
        'source_id',
        'source_other',
        'status_id',
        'three_plus_attempts',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'description',
        'badge',
        'form_data',
        'form_id',
        'consultation_booked_date',
        'no_showed_date',
        'convert_to_deal',
        'convert_deal_date',
        'reason',
        'value',
        'deal_status',
        'won_lost',
        'won_lost_date',
        'lead_type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $dates = [
        'dob',
        'consultation_booked_date',
        'no_showed_date',
        'convert_deal_date',
        'won_lost_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'clinic_id',
        'first_name',
        'last_name',
        'phone_form',
        'source_id',
        'source_other',
        'status_id',
        'three_plus_attempts',
        'email',
        'phone',
        'dob',
        'city',
        'state',
        'description',
        'badge',
        'form_data',
        'form_id',
        'consultation_booked_date',
        'no_showed_date',
        'convert_to_deal',
        'convert_deal_date',
        'reason',
        'value',
        'deal_status',
        'won_lost',
        'won_lost_date',
        'lead_type',
        'has_sms',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function registerMediaConversions(Media $media = null): void
    //public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    public function status()
    {
        return $this->belongsTo(CrmStatus::class, 'status_id');
    }

    public function getDobAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getConsultationBookedDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setConsultationBookedDateAttribute($value)
    {
        $this->attributes['consultation_booked_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getNoShowedDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setNoShowedDateAttribute($value)
    {
        $this->attributes['no_showed_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getConvertDealDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setConvertDealDateAttribute($value)
    {
        $this->attributes['convert_deal_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getWonLostDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setWonLostDateAttribute($value)
    {
        $this->attributes['won_lost_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function routeNotificationForSlack($notification)
    {
        return env('SLACK_NOTIFICATION_WEBHOOK');
    }

    
}
