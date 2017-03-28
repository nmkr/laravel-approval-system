<?php

namespace Swatkins\Approvals\Observers;

use Swatkins\Approvals\Models\Review;

class ReviewObserver
{

    public function created(Review $review)
    {
        $approval = $review->approval;
        $approval->approved = (bool) $review->approved;
        $approval->last_activity = $review->created_at;
        $approval->save();
    }

}