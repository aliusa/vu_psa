<?php

namespace App\Entity\Payments;

use App\Entity\Enum\PaymentsStatus;
use App\Entity\Invoices;
use App\Entity\Payments;
use App\Registry;

abstract class BasePayment implements PaymentInterface
{
    public const ACCEPT_URL_SALT = "accept_001";
    public const CANCEL_URL_SALT = "cancel_001";
    public const CALLBACK_URL_SALT = "callback_001";

    public function __construct()
    {
    }

    public function getNewPayment(Invoices $invoices): Payments
    {
        $payment = new Payments();
        $payment->hash = BasePayment::generateHash();
        $payment->status = PaymentsStatus::PAYMENT_STATUS_PENDING;
        $payment->invoices = $invoices;
        $payment->total = $invoices->getInvoiceTotal();
        $payment->payment_method = $this->getPaymentType();
        //$payment->request_data = WebToPay::buildRequest($data);
        //$payment->redirect_url = WebToPay::PAYSERA_PAY_URL;
        $payment->redirect_url = $this->buildUrl(Registry::getRouter()->generate('payments/test'));
        Registry::getDoctrineManager()->persist($payment);
        Registry::getDoctrineManager()->flush();

        return $payment;
    }

    public static function generateHash(): string
    {
        do {
            $hash = sha1(microtime() . rand(0, 10000));
            //siek tiek palaukiam, kad generuojant hash, laikas pasikeistu
            usleep(100);
        } while (Registry::getDoctrineManager()->getRepository(Payments::class)->findOneBy(['hash' => $hash]) != null);

        return $hash;
    }

    public function getPaymentTitle(Invoices $invoices){
        return sprintf("Apmokėjimas už sąskaitą %s", $invoices->getFormattedSeries());
    }

    public function getReturnUrl(Payments $payment){
        $control = $this->generateDataControl($payment->invoices->id, static::ACCEPT_URL_SALT);
        return Registry::getRouter()->generate('payments/return') . '?control=' . $control . "&id=" . $payment->id . '&h=' . $payment->hash;
    }

    public function getAcceptUrl(Payments $payment){
        $control = $this->generateDataControl($payment->invoices->id, static::ACCEPT_URL_SALT);
        return Registry::getRouter()->generate('payments/accept') . '?control=' . $control . "&id=" . $payment->id . '&h=' . $payment->hash;;
    }

    public function getCancelUrl(Payments $payment){
        $control = $this->generateDataControl($payment->invoices->id, static::CANCEL_URL_SALT);
        return Registry::getRouter()->generate('payments/cancel') . '?control=' . $control . "&id=" . $payment->id . '&h=' . $payment->hash;;
    }

    public function getCallbackUrl(Payments $payment){
        $control = $this->generateDataControl($payment->invoices->id, static::CALLBACK_URL_SALT);
        return Registry::getRouter()->generate('payments/callback') . '?control=' . $control . "&id=" . $payment->id . '&h=' . $payment->hash;;
    }

    /**
     * Sugeneruoja duomenu kontroles reiksme
     *
     * @param $data
     * @param string $salt
     *
     * @return string
     */
    public function generateDataControl($data, string $salt):string{
        return md5($data . $salt);
    }

    protected function buildUrl(string $path)
    {
        return $_ENV['HTTP_SCHEME'] . '://'. $_ENV['HTTP_HOST'] . $path;
    }

    /**
     * @param $data
     * @return string
     */
    public function generateSign($data)
    {
        return md5($data . $this->signPassword());
    }

    /**
     * @return string
     */
    protected function signPassword():string
    {
        return $_ENV['TEST_SIGN_PASSWORD'] ?? '';
    }
}
