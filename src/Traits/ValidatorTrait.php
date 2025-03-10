<?php

namespace App\Traits;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

trait ValidatorTrait
{
    /**
     * @Symfony\Component\Validator\Constraints\Callback
     */
    public abstract function validate(ExecutionContextInterface $context, $payload);
}
