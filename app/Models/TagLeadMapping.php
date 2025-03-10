<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagLeadMapping extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'tag_leads_mapping'; // Specify the table name

    protected $fillable = [
        'tag_id',
        'lead_id',
    ];

    protected $dates = ['deleted_at'];

    // Define relationships if necessary
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function lead()
    {
        return $this->belongsTo(CrmCustomer::class, 'lead_id');
    }
}
