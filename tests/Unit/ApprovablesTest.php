<?php

namespace Swatkins\Approvals\Tests\Unit;

use Swatkins\Approvals\Models\Widget;
use Swatkins\Approvals\Tests\BaseTestCase;

class ApprovablesTest extends BaseTestCase
{

    /** @test */
    public function it_returns_scoped_approved_denied_or_notreviewed_items ()
    {
        $requester = factory(config('approvals.requester_model'))->create(['email' => 'requester@example.com']);
        $approver = factory(config('approvals.approver_model'))->create(['email' => 'approver@example.com']);
        $widgetsToApprove = factory(Widget::class, 3)->create(['owner_id' => $requester->id]);
        foreach($widgetsToApprove as $key => $widget) {
            $widget->requestApprovalFrom($approver)->review(true, '', $approver);
        }
        $widgetsToDecline = factory(Widget::class, 4)->create(['owner_id' => $requester->id]);
        foreach($widgetsToDecline as $key => $widget) {
            $widget->requestApprovalFrom($approver)->review(false, '', $approver);
        }
        $needsReviewWidgets = factory(Widget::class, 5)->create(['owner_id' => $requester->id]);
        foreach($needsReviewWidgets as $key => $widget) {
            $widget->requestApprovalFrom($approver);
        }

        $allWidgets = Widget::all();
        $approvedWidgets = Widget::approved()->get();
        $declinedWidgets = Widget::declined()->get();
        $notReviewedWidgets = Widget::notreviewed()->get();

        $this->assertCount(12, $allWidgets);
        $this->assertCount(3, $approvedWidgets);
        $this->assertCount(4, $declinedWidgets);
        $this->assertCount(5, $notReviewedWidgets);
    }

}
