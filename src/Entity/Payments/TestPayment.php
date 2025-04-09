<?php

namespace App\Entity\Payments;

use App\Entity\Invoices;
use App\Entity\Payments;
use App\Registry;

class TestPayment extends BasePayment
{
    public function __construct()
    {
        parent::__construct();
    }

    public function pay(Invoices $invoices): Payments
    {
        $payment = $this->getNewPayment($invoices);

        $data = [
            'orderid' => $payment->getId(),
            'amount' => $invoices->getInvoiceTotal(),
            'currency' => 'EUR',
            //'country' => 'LT',

            'paytext' => $this->getPaymentTitle($invoices),
            'accepturl' => $this->buildUrl($this->getAcceptUrl($payment)),
            'cancelurl' => $this->buildUrl($this->getCancelUrl($payment)),
            'callbackurl' => $this->buildUrl($this->getCallbackUrl($payment)),
            //'p_email' => $invoices->getUser()->email,//kad nuėjus į paysera - neprašytų vėl el.pašto

            //'method' => 'test',
        ];

        $query = http_build_query($data, '', '&');
        $base64 = base64_encode($query);
        $payment->raw_request_data = $data;
        $payment->request_data = [
            'data' => $base64,
            'sign' => $this->generateSign($data['orderid'])
        ];
        Registry::getDoctrineManager()->persist($payment);
        Registry::getDoctrineManager()->flush();

        //$payment->request_data = WebToPay::buildRequest($data);
        //$payment->redirect_url = $this->buildUrl(Registry::getRouter()->generate('payments/test'));
        //$payment->redirect_url = WebToPay::PAYSERA_PAY_URL;

        return $payment;
    }

    public function getPaymentType(): string
    {
        return 'test';
    }
}
