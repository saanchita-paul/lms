<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Spatie\MediaLibrary\HasMedia;
//use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
//use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Models\Media;

class Clinic extends Model implements HasMedia
{
    use SoftDeletes;
    //use InteractsWithMedia;
    use HasMediaTrait;
    use Auditable;
    use HasFactory;
    use Notifiable;

    public const DNS_STATUS_VERIFY = 'Verify';
    public const DNS_STATUS_VERIFICATION_PENDING = 'Verification pending';
    public const DNS_STATUS_VERIFIED = 'Verified';
    const DNS_STATUS_FAILED = 'Failed, Verify again';

    protected $attributes = [
        'domain_verification' => self::DNS_STATUS_VERIFY,
    ];

    protected $appends = [
        'uploadfile',
    ];

    public const LEAD_CENTER_SELECT = [
        'No'  => 'No',
        'Yes' => 'Yes',
    ];

    public const NURTURE_AUTOMATION_SELECT = [
        'No'  => 'No',
        'Yes' => 'Yes',
    ];

    public const PATIENT_JOURNEY_CAMPAIGN_SELECT = [
        'No'  => 'No',
        'Yes' => 'Yes',
    ];

    public const TYPE_SELECT = [
        'Dental'  => 'Dental',
        'Medical' => 'Medical',
    ];

    public const STATUS_SELECT = [
        'Active'     => 'Active',
        'Terminated' => 'Terminated',
        'Dormant'    => 'Dormant',
        'On-boarding'=> 'On-boarding',
    ];

    public const PRACTICE_SPECIALTY_SELECT = [
        'General Practitioner (GP)' => 'General Practitioner (GP)',
        'Oral Surgeon'              => 'Oral Surgeon',
        'Periodontist'              => 'Periodontist',
        'Prosthodontist'            => 'Prosthodontist',
        'Multi-Specialty'           => 'Multi-Specialty',
    ];

    public const PRIMARY_SERVICES_SELECT = [
        'Dental Implants (includes Full Arch)'      => 'Dental Implants (includes Full Arch)',
        'Cosmetic Dentistry (includes Veneers)'     => 'Cosmetic Dentistry (includes Veneers)',
        'Orthodontics and/or Clear Braces'          => 'Orthodontics and/or Clear Braces',
        'Traditional GP services (includes Hygiene)'=> 'Traditional GP services (includes Hygiene)',
        'Traditional OMS services'                  => 'Traditional OMS services',
        'Traditional Perio Services'                => 'Traditional Perio Services',
        'Other'                                     => 'Other',
    ];

    public const MULTIPLE_LOCATIONS_SELECT = [
        'Yes'  => 'Yes',
        'No'   => 'No',
    ];

    public const MARKETING_MULTIPLE_LOCATIONS_SELECT = [
        'Yes'  => 'Yes',
        'No'   => 'No',
    ];

    public const CURRENT_WEBSITE_ACCURATE_SELECT = [
        'Yes'  => 'Yes',
        'No'   => 'No',
    ];

    public const WEBSITE_TYPE_SELECT = [
        'Main Website'  => 'Main Website',
        'Microsite'     => 'Microsite',
    ];

    public const GOOGLE_AD_TEAM = [
        'Team 1'        => 'Team 1',
        'Team 2'        => 'Team 2',
    ];

    public const FACEBOOK_AD_TEAM = [
        'Team 1'        => 'Team 1',
        'Team 2'        => 'Team 2',
    ];

    public const PRIMARY_SELLING_SELECT = [
        'Price: Prices are competitive in the local market'                             => 'Price: Prices are competitive in the local market',
        'Process: A combination of “One Day. One Doctor. One Location.”'                => 'Process: A combination of “One Day. One Doctor. One Location.”',
        'Doctor: For special skills like Oral Surgeons or if 1 and 2 don’t apply'     => 'Doctor: For special skills, like Oral Surgeons, or if 1 and 2 don’t apply',
    ];


    public const HERO_IMAGE_SELECT = [
        'Yes'  => 'Yes',
        'No'   => 'No',
    ];

    public const DOCTOR_BIO_SELECT = [
        'Yes'  => 'Yes',
        'No'   => 'No',
    ];

    public const TESTIMONIALS_SELECT = [
        'Yes'  => 'Yes',
        'No'   => 'No',
    ];

    public const GOOGLE_MAP_SELECT = [
        'Yes'  => 'Yes',
        'No'   => 'No',
    ];

    public const PATIENT_PHOTO_SELECT = [
        'Yes'  => 'Yes',
        'No'   => 'No',
    ];

    public const TECHNOLOGY_SELECT = [
        'Cone Beam 3D Imaging'                      => 'Cone Beam 3D Imaging',
        'CEREC technology'                          => 'CEREC technology',
        'Intraoral Scanner'                         => 'Intraoral Scanner',
        'Digital Smile Analysis Software'           => 'Digital Smile Analysis Software',
        'In-House Mill (like Zirkonzahn system)'    => 'In-House Mill (like Zirkonzahn system)',
    ];

    public const TREATMENT_AVAILABLE_SELECT = [
        'Dental Implants'  => 'Dental Implants',
        'Full-Arch Implants'   => 'Full-Arch Implants',
        'Implant-Supported Dentures'   => 'Implant-Supported Dentures',
        'Bone and Soft Tissue Grafting'   => 'Bone and Soft Tissue Grafting',
        'Sedation dentistry'   => 'Sedation dentistry',
        'Zygomatic Dental Implants'   => 'Zygomatic Dental Implants',
        'Other'   => 'Other',
    ];

    public const CONSULTATION_SELECT = [
        'Free'          => 'Free',
        'Fee (amount)'  => 'Fee (amount)',
    ];

    public const FINANCING_OPTION_SELECT = [
        'CareCredit'        => 'CareCredit',
        'Proceed'           => 'Proceed',
        'Alpheon'           => 'Alpheon',
        'Sunbit'            => 'Sunbit',
        'FinancingInHouse'  => 'Financing In-House',
        'Ally'              => 'Ally',
        'PatientFi'         => 'PatientFi',
        'Other'             => 'Other',
    ];

    public const REPORTSENDING_SELECT = [
        'None'      => 'None',
        'Daily'     => 'Daily',
        'Weekly'    => 'Weekly',
        'Biweekly'  => 'Biweekly',
        'Monthly'   => 'Monthly'
    ];

    public $table = 'clinics';

    public static $searchable = [
        'type',
        'clinic_name',
        'clinic_legal_name',
        'dr_name',
        'address',
        'email',
        'office_number',
        'hotline_phone_number',
        'website',
        'microsite_website',
        'specialty',
        'consultation_details_offers',
        'scheduling_hours',
        'emails_for_scheduling',
        'virtual_consultation',
        'services_offered_pricing',
        'financing',
        'insurance_details',
        'medicaid',
        'medicare',
        'doctor_specifics',
        'google_map_location',
        'covid_19_specifics',
        'languages_spoken',
        'consult_email_patients',
        'reply_texts_patients',
        'extra_notes',
        'callrail_company',
        'callrail_installation_script',
        'twilio_number',
        'google_analytics',
        'google_ads',
        'facebook',
        'google_ad_team',
        'facebook_ad_team',
        'custom_message',
        'timezone',
        'marketingdashboardurl',
        'schedulemeetingurl',
        'salestrainingurl',
        'leadcenteraccountmanager',
        'reportsending',
        'reportrecipients',
        'lead_center',
        'autosms',
        'autosmstext',
        'priorsmstext',
        'status',
        'success_coach_url',
        'tracking_phone'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'type',
        'clinic_name',
        'dr_name',
        'dr_fullname',
        'dr_abbreviations',
        'multiple_doctors',
        'practice_specialty',
        'multi_specialty_other',
        'primary_services',
        'primary_services_other',
        'address',
        'town_state_zip',
        'email',
        'form_notification_email',
        'office_number',
        'hotline_phone_number',
        'website',
        'microsite_website',
        'specialty',
        'office_hours',
        'holidays',
        'multiple_locations',
        'marketing_multiple_locations',
        'multiple_localtion_details',
        'current_website_accurate',
        'needs_updated',
        'practice_management_system',
        'website_type',
        'area',
        'primary_selling',
        'primary_selling_other',
        'hero_image',
        'docto_bio',
        'testimonials',
        'google_map',
        'patient_photos',
        'technology',
        'available_treatment',
        'available_treatment_other',
        'practice_different',
        'another_company',
        'other_marketing_notes',
        'media_budget',
        'full_arch_price',
        'overdenture_price',
        'single_implant_price',
        'consultation',
        'consultation_price',
        'financing_options',
        'financing_options_other',
        'location_specifics',
        'appointment_confirmations',
        'consultation_details_offers',
        'scheduling_hours',
        'primary_treatment_coordinator',
        'emails_for_scheduling',
        'virtual_consultation',
        'services_offered_pricing',
        'financing',
        'insurance_details',
        'medicaid',
        'medicare',
        'doctor_specifics',
        'google_map_location',
        'covid_19_specifics',
        'languages_spoken',
        'consult_email_patients',
        'reply_texts_patients',
        'extra_notes',
        'domain_verification',
        'callrail_company',
        'twilio_number',
        'twilio_subid',
        'twilio_token',
        'google_analytics',
        'google_ads',
        'facebook',
        'google_ad_team',
        'facebook_ad_team',
        'nurture_automation',
        'usertestimonials',
        'listtechnology',
        'smtpUsername',
        'smtpServer',
        'smtpPort',
        'smtpPassword',
        'smtpMailer',
        'patient_journey_campaign',
        'link1',
        'link2',
        'link3',
        'custom_message',
        'timezone',
        'marketingdashboardurl',
        'schedulemeetingurl',
        'salestrainingurl',
        'success_coach_url',
        'leadcenteraccountmanager',
        'reportsending',
        'reportrecipients',
        'lead_center',
        'autosms',
        'autosmstext',
        'priorsmstext',
        'workingdaysmstext',
        'nonworkingdaysmstext',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
        'nexhealthselection',
        'subdomain',
        'location',
        'nexhealthkey',
        'emailcampaign',
        'dih_account_details',
        // Newly added fields
        'doctors_biography',
        'doctors_biography_description',
        'doctors_biography_summary',
        'doctors_biography_summary_description',
        'marketing_message',
        'marketing_message_description',
        'clinic_logo',
        'automation_campaign',
        'tracking_phone',
        'immediate_sms',
        'immediate_email',
        'whereby_link',
        'assistant_id',
        'auto_reply_email',
        'auto_reply_sms',
        'vapi_assistant_id',
        'vapi_phone_number_id'
    ];

    //public function registerMediaConversions(Media $media = null): void
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function managers()
    {
        return $this->belongsToMany(User::class);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getUploadfileAttribute()
    {
        return $this->getMedia('uploadfile')->last();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('clinic_logo');
    }

    public function appointment_availability()
    {
        return $this->hasOne(AppointmentAvailability::class);
    }

    public function customers()
    {
        return $this->hasMany(CrmCustomer::class);
    }

    // Define the relationship
    public function dnsRecords()
    {
        return $this->hasMany(ClinicDnsRecord::class);
    }
}
