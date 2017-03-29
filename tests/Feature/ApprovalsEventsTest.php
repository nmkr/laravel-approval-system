<?php

namespace Swatkins\Approvals\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Swatkins\Approvals\Events\ApprovalWasApproved;
use Swatkins\Approvals\Events\ApprovalWasDeclined;
use Swatkins\Approvals\Events\ApprovalWasRequested;
use Swatkins\Approvals\Models\Approval;
use Swatkins\Approvals\Models\Review;
use Swatkins\Approvals\Models\Widget;
use Swatkins\Approvals\Tests\BaseTestCase;

class ApprovalsEventsTest extends BaseTestCase
{

    /** @test */
    public function it_fires_an_event_when_an_approval_is_requested ()
    {


        Event::listen(ApprovalWasRequested::class, function ($event) {
            $this->assertInstanceOf(Approval::class, $event->getApproval());
            $this->assertEquals('requester@example.com', $event->getApproval()->requester->email);
            $this->assertEquals('approver@example.com', $event->getApproval()->approver->email);
        });
        $this->expectsEvents(ApprovalWasRequested::class);

        $requester = factory(config('approvals.requester_model'))->create(['email' => 'requester@example.com']);
        $approver = factory(config('approvals.approver_model'))->create(['email' => 'approver@example.com']);
        $widget = factory(Widget::class)->create(['owner_id' => $requester->id]);
        $widget->requestApprovalFrom($approver);

    }

    /** @test */
    public function it_fires_an_event_when_an_approval_is_denied ()
    {

        Event::listen(ApprovalWasDeclined::class, function ($event) {
            $this->assertInstanceOf(Approval::class, $event->getApproval());
            $this->assertInstanceOf(Review::class, $event->getReview());
        });
        $this->expectsEvents(ApprovalWasDeclined::class);

        $requester = factory(config('approvals.requester_model'))->create(['email' => 'requester@example.com']);
        $approver = factory(config('approvals.approver_model'))->create(['email' => 'approver@example.com']);
        $widget = factory(Widget::class)->create(['owner_id' => $requester->id]);
        $approval = $widget->requestApprovalFrom($approver);

        $approval->review(false, 'This approval has been declined', $approver);

    }

    /** @test */
    public function it_fires_an_event_when_an_approval_is_approved ()
    {

        Event::listen(ApprovalWasApproved::class, function ($event) {
            $this->assertInstanceOf(Approval::class, $event->getApproval());
            $this->assertInstanceOf(Review::class, $event->getReview());
        });
        $this->expectsEvents(ApprovalWasApproved::class);

        $requester = factory(config('approvals.requester_model'))->create(['email' => 'requester@example.com']);
        $approver = factory(config('approvals.approver_model'))->create(['email' => 'approver@example.com']);
        $widget = factory(Widget::class)->create(['owner_id' => $requester->id]);
        $approval = $widget->requestApprovalFrom($approver);

        $approval->review(true, 'This approval has been approved', $approver);

    }


}
