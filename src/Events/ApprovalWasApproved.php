<?php

namespace Swatkins\Approvals\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Swatkins\Approvals\Models\Approval;
use Swatkins\Approvals\Models\Review;

class ApprovalWasApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $approval;
    private $review;

    /**
     * @return mixed
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param mixed $review
     */
    public function setReview($review)
    {
        $this->review = $review;
    }

    /**
     * @return mixed
     */
    public function getApproval()
    {
        return $this->approval;
    }

    /**
     * @param mixed $approval
     */
    public function setApproval($approval)
    {
        $this->approval = $approval;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Approval $approval, Review $review)
    {
        $this->setApproval($approval);
        $this->setReview($review);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
