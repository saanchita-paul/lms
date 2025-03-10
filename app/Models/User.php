<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Spatie\MediaLibrary\HasMedia;
//use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
//use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\Models\Media;


class User extends Authenticatable implements HasMedia
{
    use SoftDeletes;
    use Notifiable;
    use HasApiTokens;
    //use InteractsWithMedia;
    use HasMediaTrait;
    use Auditable;
    use HasFactory;

    public $table = 'users';

    protected $appends = [
        'profile_pic',
    ];

    protected $hidden = [
        'remember_token',
        'password',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'phone',
        'password',
        'remember_token',
        'consultemail',
        'last_login_date',
        'user_email_verified_at',
        'activation_token',
        'two_factor_enabled',
        'two_factor_type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function getIsManagerAttribute()
    {
        return $this->roles()->where('id', 2)->exists();
    }

    public function getIsLeadCenterAssociateAttribute()
    {
        return $this->roles()->where('id', 4)->exists();
    }

    //public function registerMediaConversions(Media $media = null): void
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function managerClinics()
    {
        return $this->belongsToMany(Clinic::class);
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function getProfilePicAttribute()
    {
        // $file = $this->getMedia('profile_pic')->last();
        // if ($file) {
        //     $file->url       = $file->getUrl();
        //     $file->thumbnail = $file->getUrl('thumb');
        //     $file->preview   = $file->getUrl('preview');
        // }

        // return $file;
        $file = $this->getMedia('profile_pic')->last();

        if ($file) {
            $baseUrl = config('app.url'); // Use your application's base URL here

            $file->url = str_replace($baseUrl, '', $file->getUrl());
            $file->thumbnail = str_replace($baseUrl, '', $file->getUrl('thumb'));
            $file->preview = str_replace($baseUrl, '', $file->getUrl('preview'));
        }

        return $file;

    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    #Updaste record function
    public static function updateRecord($updateArr,$id)
    {
        return self::where('id',$id)->update($updateArr);
    }

    public function setting()
    {
        return $this->hasOne(Setting::class);
    }


    public function routeNotificationForTwilio($notification)
    {
        return $this->phone;
    }
}
