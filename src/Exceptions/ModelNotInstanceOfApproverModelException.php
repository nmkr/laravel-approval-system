<?php

namespace Swatkins\Approvals\Exceptions;

use Throwable;

class ModelNotInstanceOfApproverModelException extends \Exception
{

    public function __construct($message = "The model given is not an instance of the approvals.approval_model config value.", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}