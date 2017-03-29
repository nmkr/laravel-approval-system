<?php

namespace Swatkins\Approvals\Observers;

use Illuminate\Support\Facades\Event;
use Swatkins\Approvals\Events\ApprovalWasRequested;
use Swatkins\Approvals\Models\Approval;

class ApprovalObserver
{

    public function created(Approval $approval)
    {
        Event::fire(new ApprovalWasRequested($approval));
    }

}