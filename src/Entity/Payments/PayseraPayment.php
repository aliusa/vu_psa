<?php

namespace App\Entity\Payments;

use App\Entity\Invoices;
use App\Entity\Payments;
use App\Registry;

class PayseraPayment extends BasePayment
{
    public function __construct()
    {
        parent::__construct();
        $this->requireLibs();
    }

    public function pay(Invoices $invoices): Payments
    {
        $payment = $this->getNewPayment($invoices);

        $data = [
            'projectid' => $_ENV['PAYSERA_PROJECT_ID'],
            'sign_password' => $_ENV['PAYSERA_SIGN_PASSWORD'],
            'test' => $_ENV['PAYSERA_TEST'] ?? 1,

            'orderid' => $payment->getId(),
            'amount' => $invoices->getInvoiceTotal(),
            'currency' => 'EUR',
            //'country' => 'LT',

            'paytext' => $this->getPaymentTitle($invoices),
            'accepturl' => $this->buildUrl($this->getAcceptUrl($payment)),
            'cancelurl' => $this->buildUrl($this->getCancelUrl($payment)),
            'callbackurl' => $this->buildUrl($this->getCallbackUrl($payment)),
            'p_email' => $invoices->getUser()->email,//kad nuėjus į paysera - neprašytų vėl el.pašto
        ];

        $payment->raw_request_data = $data;
        $payment->request_data = \WebToPay::buildRequest($data);
        $payment->redirect_url = \WebToPay::PAYSERA_PAY_URL;

        return $payment;
    }

    public function getPaymentType(): string
    {
        return 'paysera';
    }

    protected function requireLibs(){
        require_once Registry::getKernel()->getProjectDir() . str_replace('/', DIRECTORY_SEPARATOR, '/vendors/Paysera/WebToPay.php');
    }
}
