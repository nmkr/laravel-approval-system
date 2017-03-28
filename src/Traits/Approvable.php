<?php

namespace Swatkins\Approvals\Traits;

use Swatkins\Approvals\Exceptions\ApprovalHasAlreadyBeenRequestedException;
use Swatkins\Approvals\Exceptions\ModelNotInstanceOfApproverModelException;
use Swatkins\Approvals\Models\Approval;

trait Approvable
{

    public function requestApprovalFrom($approver)
    {
        $approverModel = config('approvals.approver_model');
        if (!$approver instanceof $approverModel) {
            throw new ModelNotInstanceOfApproverModelException;
        }

        if (static::has('approval')->get()->count()) {
            throw new ApprovalHasAlreadyBeenRequestedException;
        }

        return $this->createNewApproval($approver);

    }

    private function createNewApproval($approver)
    {
        return Approval::create([
            'requester_id'    => $this->getModelOwner()->id,
            'approver_id'     => $approver->id,
            'approvable_type' => static::class,
            'approvable_id'   => $this->id,
            'approved'        => false,
            'last_activity'   => null
        ]);
    }

    public function approval()
    {
        return $this->morphOne(Approval::class, 'approvable');
    }

    abstract public function morphOne($related, $name, $type = null, $id = null, $localKey = null);

    abstract public function getModelOwner();

}