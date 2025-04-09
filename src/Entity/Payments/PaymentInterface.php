<?php

namespace App\Entity\Payments;

use App\Entity\Invoices;
use App\Entity\Payments;

interface PaymentInterface
{
    public function pay(Invoices $invoices): Payments;

    public function getPaymentType(): string;
}
