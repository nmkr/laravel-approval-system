<?php

namespace Swatkins\Approvals\Exceptions;

use Throwable;

class ApprovalHasAlreadyBeenRequestedException extends \Exception
{

    public function __construct($message = "This model has already requested approval.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}