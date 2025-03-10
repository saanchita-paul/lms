<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Spatie\MediaLibrary\HasMedia;
//use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
//use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Models\Media;

class ClinicUser extends Model implements HasMedia
{
    //use SoftDeletes;
    //use InteractsWithMedia;
    use HasMediaTrait;
    use Auditable;
    use HasFactory;

    public $table = 'clinic_user';
    public $timestamps = false;
    public $deleted_at = false;

    protected $fillable = [
        'clinic_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userRole()
    {
        return $this->belongsTo(RoleUser::class, 'user_id','user_id');
    }

}
