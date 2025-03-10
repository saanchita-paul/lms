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


class DataUpload extends Model implements HasMedia
{
    use SoftDeletes;
    use Notifiable;
    use InteractsWithMedia;
    //use HasMediaTrait;
    use Auditable;
    use HasFactory;

    protected $appends = [
        'uploadfile',
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

    public function getUploadfileAttribute()
    {
        return $this->getMedia('uploadfile')->last();
    }
   
    
}
