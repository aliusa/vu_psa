<?php

namespace App\Controller;

use App\Entity\Enum\PaymentsStatus;
use App\Entity\Invoices;
use App\Entity\Payments;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
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
            return $this->redirect($this->generateUrl('app_home'));
        }

        $params = $this->request->request->all();
        //$invoice = $this->getInvoiceFromPaymentData($params);

        /** @var Payments\BasePayment $paymentType */
        $paymentType = match ($this->request->get('method')) {
            'test' => new Payments\TestPayment(),
            'paysera' => new Payments\PayseraPayment(),
            default => null,
        };
        if (!$paymentType) {
            return $this->redirect($this->generateUrl('app_home'));
        }

        $payment = $paymentType->pay($invoices);

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
        $testPayment = new Payments\TestPayment();
        if ($sign === $testPayment->generateSign($result['orderid'])) {
            return $this->render('payments/pay.test.twig', [
                'payment_data' => $result,
                'callback_data' => [
                    'orderid' => $result['orderid'],
                    'amount' => $result['amount'],
                    'currency' => $result['currency'],
                    'payment' => $result['payment'] ?? null,
                    'sign' => $testPayment->generateSign($result['orderid'], $_ENV['TEST_SIGN_PASSWORD'])
                ],
            ]);
        } else {
            //
        }

        return $this->redirect($this->generateUrl('app_home'));
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/payments/cancel', name: 'payments/cancel')]
    public function cancel(EntityManagerInterface $entityManager): Response
    {
        $payment = $this->getPaymentFromRequest(static::CANCEL_URL_SALT);
        if (!$payment) {
            return $this->redirect('app_home');
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

    #[Route('/payments/callback', name: 'payments/callback')]
    public function callback(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
    ): Response
    {
        $data = $this->request->request->all();
        $logger->debug('payments/callback', $data, $this->request->query->all());

        $payment = $this->getPaymentFromRequest(static::ACCEPT_URL_SALT);
        if (!$payment) {
            return $this->json('');
        }

        $payment->response_data = $data;
        $payment->return_data = "OK:" . $payment->getId();
        $payment->status = PaymentsStatus::PAYMENT_STATUS_SUCCESS;
        $payment->payment_date = new \DateTime();
        $payment->invoices->is_paid = $payment->payment_date;
        $this->managerRegistry->getManager()->persist($payment);
        $this->managerRegistry->getManager()->flush();

        return (new Response('OK'))->send();
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/payments/accept', name: 'payments/accept')]
    public function accept(EntityManagerInterface $entityManager): Response
    {
        $payment = $this->getPaymentFromRequest(static::ACCEPT_URL_SALT);
        if (!$payment) {
            $this->addFlash('danger', 'Įvyko klaida');
        }

        if ($payment->payment_date) {
            $this->addFlash('success', 'Sąskaita apmokėta');
        }

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
        if (!isset($params['orderid'])) {
            return null;
        }

        return $this->managerRegistry->getManager()->getRepository(Payments::class)->findOneBy(['id' => $params['orderid']]);
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
