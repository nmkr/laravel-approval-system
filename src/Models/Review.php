<?php

namespace Swatkins\Approvals\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    protected $fillable = [
        'author_id',
        'approval_id',
        'body',
        'approved',
    ];

    protected $appends = [
        'is_declined'
    ];

    public function author()
    {
        return $this->belongsTo(config('approvals.requester_model'));
    }

    public function approval()
    {
        return $this->belongsTo(Approval::class);
    }

    public function setApprovedAttribute($value)
    {
        $this->attributes['approved'] = (boolean) $value;
    }

    public function getApprovedAttribute()
    {
        return (boolean) $this->attributes['approved'];
    }

    public function getIsDeclinedAttribute()
    {
        return ($this->approved === false);
    }
}
