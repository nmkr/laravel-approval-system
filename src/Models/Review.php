<?php

namespace Swatkins\Approvals\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    public function author()
    {
        return $this->belongsTo(config('approvals.requester_model'));
    }

    public function approval()
    {
        return $this->belongsTo(Approval::class);
    }
}
