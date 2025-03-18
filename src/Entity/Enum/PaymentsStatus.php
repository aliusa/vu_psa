<?php

namespace App\Entity\Enum;

class PaymentsStatus
{
    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_SUCCESS = 'success';
    public const PAYMENT_STATUS_FAILED = 'failed';
}
