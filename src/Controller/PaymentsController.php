<?php

namespace App\Controller;

use App\Entity\Enum\PaymentsStatus;
use App\Entity\Invoices;
use App\Entity\Payments;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PaymentsController extends BaseController
{
    const ACCEPT_URL_SALT = "accept_001";
    const CANCEL_URL_SALT = "cancel_001";
    const CALLBACK_URL_SALT = "callback_001";

    #[IsGranted('ROLE_USER')]
    #[Route('/payments/pay/{id}', name: 'payments/pay', requirements: ['id' => Requirement::DIGITS])]
    public function pay(
        EntityManagerInterface $entityManager,
        #[MapEntity(id: 'id')]
        Invoices $invoices,
    ): Response
    {
        if ($invoices->is_paid) {
            return $this->redirect($this->generateUrl('app_index'));
        }

        $params = $this->request->request->all();
        //$invoice = $this->getInvoiceFromPaymentData($params);

        $payment = new Payments();
        $payment->status = PaymentsStatus::PAYMENT_STATUS_PENDING;
        $payment->invoices = $invoices;
        $payment->total = $invoices->getInvoiceTotal();
        $payment->payment_method = 'test';
        //$payment->request_data = WebToPay::buildRequest($data);
        //$payment->redirect_url = WebToPay::PAYSERA_PAY_URL;
        $payment->redirect_url = $this->buildUrl($this->generateUrl('payments/test'));
        $this->managerRegistry->getManager()->persist($payment);
        $this->managerRegistry->getManager()->flush();

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
        $this->managerRegistry->getManager()->persist($payment);
        $this->managerRegistry->getManager()->flush();

        //$payment->request_data = WebToPay::buildRequest($data);
        //$payment->redirect_url = WebToPay::PAYSERA_PAY_URL;

        return $this->render('payments/pay.twig', [
            'payment' => $payment,
        ]);
    }



    #[IsGranted('ROLE_USER')]
    #[Route('/payments/test', name: 'payments/test')]
    public function test(EntityManagerInterface $entityManager): Response
    {
        $data = $this->request->request->getString('data');
        $sign = $this->request->request->getString('sign');
        parse_str(base64_decode($data), $result);
        if ($sign === $this->generateSign($result['orderid'])) {
            return $this->render('payments/pay.test.twig', [
                'payment_data' => $result,
                'callback_data' => [
                    'orderid' => $result['orderid'],
                    'amount' => $result['amount'],
                    'currency' => $result['currency'],
                    'payment' => $result['payment'] ?? null,
                    'sign' => $this->generateSign($result['orderid'], $_ENV['TEST_SIGN_PASSWORD'])
                ],
            ]);
        } else {
            //
        }

        return $this->redirect($this->generateUrl('app_index'));
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/payments/cancel', name: 'payments/cancel')]
    public function cancel(EntityManagerInterface $entityManager): Response
    {
        $payment = $this->getPaymentFromRequest(static::CANCEL_URL_SALT);
        if (!$payment) {
            return $this->redirect('app_index');
        }

        //$payment->response_data = $data;
        //$payment->return_data = "OK:" . $payment->getId();
        $payment->status = PaymentsStatus::PAYMENT_STATUS_FAILED;
        //$payment->payment_date = new \DateTime();
        //$payment->invoices->is_paid = $payment->payment_date;
        $this->managerRegistry->getManager()->persist($payment);
        $this->managerRegistry->getManager()->flush();

        $this->addFlash('danger', 'Payment canceled');
        /** @see UsersController::invoicesId() */
        return $this->redirect($this->generateUrl('invoicesId', [
            'id' => $payment->invoices->getId(),
        ]));
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/payments/callback', name: 'payments/callback')]
    public function callback(EntityManagerInterface $entityManager): Response
    {
        $data = $this->request->request->all();
        $payment = $this->getPaymentFromOrderid($data);
        if (!$payment) {
            return $this->redirect('app_index');
        }

        $payment->response_data = $data;
        $payment->return_data = "OK:" . $payment->getId();
        $payment->status = PaymentsStatus::PAYMENT_STATUS_SUCCESS;
        $payment->payment_date = new \DateTime();
        $payment->invoices->is_paid = $payment->payment_date;
        $this->managerRegistry->getManager()->persist($payment);
        $this->managerRegistry->getManager()->flush();

        return $this->json($payment->return_data);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/payments/accept', name: 'payments/accept')]
    public function accept(EntityManagerInterface $entityManager): Response
    {
        $payment = $this->getPaymentFromRequest(static::ACCEPT_URL_SALT);
        if ($payment) {
            //
        }
        $this->addFlash('success', 'Payment accepted');

        /** @see UsersController::invoicesId() */
        return $this->redirect($this->generateUrl('invoicesId', [
            'id' => $payment->invoices->getId(),
        ]));
    }

    /**
     * @param int $id
     * @return Invoices|null
     */
    public function getPaymentFromOrderid(array $params): ?Payments
    {
        return $this->managerRegistry->getManager()->getRepository(Payments::class)->findOneBy(['id' => $params['orderid']]);
    }

    public function getReturnUrl(Payments $payment){
        $control = $this->generateDataControl($payment->invoices->id, static::ACCEPT_URL_SALT);
        return $this->generateUrl('payments/return') . '?control=' . $control . "&id=" . $payment->id . '&h=' . $payment->hash;
    }

    public function getAcceptUrl(Payments $payment){
        $control = $this->generateDataControl($payment->invoices->id, static::ACCEPT_URL_SALT);
        return $this->generateUrl('payments/accept') . '?control=' . $control . "&id=" . $payment->id . '&h=' . $payment->hash;;
    }

    public function getCancelUrl(Payments $payment){
        $control = $this->generateDataControl($payment->invoices->id, static::CANCEL_URL_SALT);
        return $this->generateUrl('payments/cancel') . '?control=' . $control . "&id=" . $payment->id . '&h=' . $payment->hash;;
    }

    public function getCallbackUrl(Payments $payment){
        $control = $this->generateDataControl($payment->invoices->id, static::CALLBACK_URL_SALT);
        return $this->generateUrl('payments/callback') . '?control=' . $control . "&id=" . $payment->id . '&h=' . $payment->hash;;
    }
    public function getPaymentTitle(Invoices $invoices){
        return sprintf("Apmokėjimas už sąskaitą %s", $invoices->getFormattedSeries());
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

    protected function getPaymentFromRequest($salt = null, bool $validateUser = true):?Payments
    {
        $orderid = $this->request->request->getString('orderid');
        $amount = $this->request->request->getString('amount');
        $currency = $this->request->request->getString('currency');
        $payment = $this->request->request->getString('payment');
        $sign = $this->request->request->getString('sign');

        if ($salt) {
            //
            $control = $this->request->query->getString('control');
            $paymentsId = $this->request->query->getString('id');
            $hash = $this->request->query->getString('h');

            //validate
            //if (md5($hash . $salt) == $control) {
                /** @var Payments|null $payment */
                $payment = $this->managerRegistry->getManager()->getRepository(Payments::class)->findOneBy(['hash' => $hash]);
                return $payment;
            //}
        }
        return null;
    }
}
