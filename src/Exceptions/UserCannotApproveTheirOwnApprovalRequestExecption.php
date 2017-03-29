<?php

namespace Swatkins\Approvals\Exceptions;

use Throwable;

class UserCannotApproveTheirOwnApprovalRequestExecption extends \Exception
{

    public function __construct($message = "The user does not have the authority to approve this item.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}