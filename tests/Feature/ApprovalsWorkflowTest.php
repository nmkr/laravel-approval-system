<?php

namespace Swatkins\Approvals\Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Swatkins\Approvals\Models\Approval;
use Swatkins\Approvals\Models\Review;
use Swatkins\Approvals\Models\Widget;
use Swatkins\Approvals\Tests\BaseTestCase;

class ApprovalsWorkflowTest extends BaseTestCase
{

    /** @test */
    public function user_is_able_to_submit_a_model_for_approval()
    {

        $requester = factory(config('approvals.requester_model'))->create([ 'email' => 'requester@example.com' ]);
        $approver = factory(config('approvals.approver_model'))->create([ 'email' => 'approver@example.com' ]);
        $widget = factory(Widget::class)->create([ 'name' => 'Test Widget', 'owner_id' => $requester->id ]);

        $approval = $widget->requestApprovalFrom($approver);
        $this->assertInstanceOf(Approval::class, $approval); // sanity check that we actually receive an Approval instance

        $this->assertEquals('requester@example.com', $approval->requester->email);
        $this->assertEquals('approver@example.com', $approval->approver->email);
        $this->assertEquals('Test Widget', $approval->approvable->name);
        $this->assertFalse($approval->approved);
        $this->assertNull($approval->last_activity);

    }

    /** @test */
    public function approver_is_able_to_review_an_approval_request ()
    {

        $requester = factory(config('approvals.requester_model'))->create([ 'email' => 'requester@example.com' ]);
        $approver = factory(config('approvals.approver_model'))->create([ 'email' => 'approver@example.com' ]);
        $widget = factory(Widget::class)->create([ 'name' => 'Test Widget', 'owner_id' => $requester->id ]);
        $approval = $widget->requestApprovalFrom($approver);
        $this->assertInstanceOf(Approval::class, $approval); // sanity check that we actually receive an Approval instance
        Auth::login($approver);

        $review = $approval->review(false, 'This is a test review');

        $this->assertEquals($approval->id, $widget->approval->id);
        $this->assertInstanceOf(Review::class, $review);
        $this->assertEquals('approver@example.com', $review->author->email);
        $this->assertEquals($approval->id, $review->approval->id);
        $this->assertFalse($review->approved);
        $this->assertEquals('This is a test review', $review->body);

    }

    /** @test */
    public function a_review_triggers_an_update_of_the_parent_approval ()
    {

        $requester = factory(config('approvals.requester_model'))->create([ 'email' => 'requester@example.com' ]);
        $approver = factory(config('approvals.approver_model'))->create([ 'email' => 'approver@example.com' ]);
        $widget = factory(Widget::class)->create([ 'name' => 'Test Widget', 'owner_id' => $requester->id ]);
        $approval = $widget->requestApprovalFrom($approver);
        $this->assertInstanceOf(Approval::class, $approval); // sanity check that we actually receive an Approval instance
        Auth::login($approver);

        $this->assertFalse($approval->approved);
        $this->assertNull($approval->last_activity);

        $review = $approval->review(true, 'This is a test review');
        $approval = $approval->fresh();

        $this->assertTrue($approval->approved);
        $this->assertNotNull($approval->last_activity);
        $this->assertEquals($review->created_at, $approval->last_activity);

    }

}
