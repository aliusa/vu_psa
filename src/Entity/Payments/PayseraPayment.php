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
            'orderid' => $payment->getId(),
            'accepturl' => $this->buildUrl($this->getAcceptUrl($payment)),
            'cancelurl' => $this->buildUrl($this->getCancelUrl($payment)),
            'callbackurl' => $this->buildUrl($this->getCallbackUrl($payment)),
            'version' => '1.6',
            'lang' => 'LIT',
            'amount' => $invoices->getInvoiceTotal(),
            'currency' => 'EUR',
            //'payment' => '',
            //'country' => 'LT',
            'paytext' => $this->getPaymentTitle($invoices),
            'p_firstname' => $invoices->getUser()->first_name,
            'p_lastname' => $invoices->getUser()->last_name,
            'p_email' => $invoices->getUser()->email,//kad nuėjus į paysera - neprašytų vėl el.pašto
            //'p_street' => '',
            //'p_city' => '',
            //'p_state' => '',
            //'p_zip' => '',
            //'p_countrycode' => '',
            'test' => $_ENV['PAYSERA_TEST'] ?? 1,
            //'only_payments' => '',
            //'disallow_payments' => '',
            //'time_limit' => '',
            //'personcode' => '',
            //'developerid' => '',
            //'buyer_consent' => '',

            'sign_password' => $_ENV['PAYSERA_SIGN_PASSWORD'],
        ];

        $payment->raw_request_data = $data;
        $payment->request_data = \WebToPay::buildRequest($data);
        $payment->redirect_url = \WebToPay::PAYSERA_PAY_URL;
        Registry::getDoctrineManager()->persist($payment);
        Registry::getDoctrineManager()->flush();

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
