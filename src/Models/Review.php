<?php

namespace Swatkins\Approvals\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    public function approval()
    {
        return $this->belongsTo(Approval::class);
    }
}
