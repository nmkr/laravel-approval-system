<?php

namespace Swatkins\Approvals\Observers;

use Illuminate\Support\Facades\Event;
use Swatkins\Approvals\Events\ApprovalWasApproved;
use Swatkins\Approvals\Events\ApprovalWasDeclined;
use Swatkins\Approvals\Models\Review;

class ReviewObserver
{

    public function created(Review $review)
    {
        $approval = $review->approval;
        $approval->approved = (bool) $review->approved;
        $approval->last_activity = $review->created_at;
        $approval->save();

        if (!$review->approved) {
            Event::fire(new ApprovalWasDeclined($approval, $review));
        } else {
            Event::fire(new ApprovalWasApproved($approval, $review));
        }
    }

}