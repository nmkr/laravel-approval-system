<?php

namespace Swatkins\Approvals\Tests\Stubs;

use Illuminate\Foundation\Auth\User as Eloquent;
use Swatkins\Approvals\Traits\ApprovesItems;

class User extends Eloquent
{

    use ApprovesItems;

    public $approvalGranted = true;

    public function canApprove($item) : bool
    {
        return $this->approvalGranted;
    }

}