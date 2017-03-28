<?php

namespace Swatkins\Approvals\Models;

use Illuminate\Database\Eloquent\Model;
use Swatkins\Approvals\Traits\Approvable;

class Widget extends Model
{
    use Approvable;

    public function owner()
    {
        return $this->belongsTo(config('approvals.requester_model'), 'owner_id');
    }

    public function getModelOwner()
    {
        return $this->owner;
    }
}
