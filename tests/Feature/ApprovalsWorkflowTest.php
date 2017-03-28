<?php

namespace Tests\Unit;

use Swatkins\Approvals\Models\Approval;
use Swatkins\Approvals\Models\Widget;
use Tests\BaseTestCase;

class ApprovalsWorkflowTest extends BaseTestCase
{

    /**
     * @test
     */
    public function user_is_able_to_submit_a_model_for_approval()
    {
        $requester = factory(config('approvals.requester_model'))->create([ 'email' => 'requester@example.com' ]);
        $approver = factory(config('approvals.approver_model'))->create([ 'email' => 'approver@example.com' ]);
        $widget = factory(Widget::class)->create([ 'name' => 'Test Widget', 'owner_id' => $requester->id ]);

        $approval = $widget->requestApprovalFrom($approver);
        $this->assertInstanceOf(Approval::class, $approval); // sanity check that we actually receive an Approval instance
        $approval->load(['requester', 'approver', 'approvable']);

        $this->assertEquals('requester@example.com', $approval->requester->email);
        $this->assertEquals('approver@example.com', $approval->approver->email);
        $this->assertEquals('Test Widget', $approval->approvable->name);
        $this->assertFalse($approval->approved);
        $this->assertNull($approval->last_activity);

    }

}
