<?php

namespace Swatkins\Approvals\Traits;

use Swatkins\Approvals\Models\Approval;

trait Approvable
{

    public function approval()
    {
        return $this->morphOne(Approval::class);
    }

}