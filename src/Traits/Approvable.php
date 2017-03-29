<?php

namespace Swatkins\Approvals\Traits;

use Illuminate\Database\Eloquent\Builder;
use Swatkins\Approvals\Exceptions\UserCannotApproveTheirOwnApprovalRequestExecption;
use Swatkins\Approvals\Models\Approval;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Swatkins\Approvals\Exceptions\ApprovalHasAlreadyBeenRequestedException;
use Swatkins\Approvals\Exceptions\ModelNotInstanceOfApproverModelException;

trait Approvable
{

    public function requestApprovalFrom($approver) : Approval
    {
        $approverModel = config('approvals.approver_model');
        if (!$approver instanceof $approverModel) {
            throw new ModelNotInstanceOfApproverModelException;
        }
        $this->load('approval');
        if ($this->approval) {
            throw new ApprovalHasAlreadyBeenRequestedException;
        }

        if ($approver->id === $this->owner->id) {
            throw new UserCannotApproveTheirOwnApprovalRequestExecption;
        }

        return $this->createNewApproval($approver);

    }

    private function createNewApproval($approver) : Approval
    {
        $approval = Approval::create([
            'requester_id'    => (int) $this->getModelOwner()->id,
            'approver_id'     => (int) $approver->id,
            'approvable_type' => static::class,
            'approvable_id'   => (int) $this->id,
            'approved'        => false,
            'last_activity'   => null
        ]);
        $approval->load(['requester', 'approver', 'approvable']);
        return $approval;
    }

    public function approval() : MorphOne
    {
        return $this->morphOne(Approval::class, 'approvable');
    }

    public function scopeApproved($query)
    {
        return $query->whereHas('approval.reviews', function ($subQuery) {
            $subQuery->where('approved', 1);
        });
    }

    public function scopeDeclined($query)
    {
        return $query->whereHas('approval.reviews', function ($subQuery) {
            $subQuery->where('approved', 0);
        });
    }

    public function scopeNotreviewed($query)
    {
        return $query->has('approval')->doesntHave('approval.reviews');
    }

    abstract public function morphOne($related, $name, $type = null, $id = null, $localKey = null);

    abstract public function getModelOwner();

}