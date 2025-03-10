<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['tagName','clinic_id'];

    protected $dates = ['deleted_at'];
    

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function leads()
    {
        return $this->belongsToMany(CrmCustomer::class, 'tag_leads_mapping', 'tag_id', 'lead_id');
    }

    // Define the relationship with TagLeadMapping
    public function tagLeadMappings()
    {
        return $this->hasMany(TagLeadMapping::class, 'tag_id');
    }
}
