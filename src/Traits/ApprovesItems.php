<?php

namespace Swatkins\Approvals\Traits;

trait ApprovesItems
{

    abstract public function canApprove($item) : bool ;

}