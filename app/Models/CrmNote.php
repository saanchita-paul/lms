<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;
use Auth;

class CrmNote extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'crm_notes';

    public const USER_ROLE_ADMIN = 1;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'customer_id',
        'note',
        'user_id',
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
        return $this->belongsTo(CrmCustomer::class, 'customer_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function crmcustomer()
    {
        return $this->belongsTo(CrmCustomer::class, 'customer_id');
    }


    public static function boot()
    {
       parent::boot();
       static::creating(function($model)
       {
           $user = Auth::user();
           if(isset($user->id)){
                $model->user_id = $user->id;
           }
       });
       static::updating(function($model)
       {
           $user = Auth::user();
           $model->user_id = $user->id;
       });
   }
}
