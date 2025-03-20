<?php

namespace App\Controller;

use App\Entity\Invoices;
use App\Entity\UsersObjects;
use App\Entity\UsersObjectsServices;
use App\Entity\UsersObjectsServicesBundles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UsersController extends BaseController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/users/change_object', methods: ['GET'])]
    public function change_object(EntityManagerInterface $entityManager): Response
    {
        $usersObject = $entityManager->getRepository(UsersObjects::class)->findOneBy([
            'id' => $this->request->get('id'),
            'users' => $this->getUser(),
        ]);
        if ($usersObject) {
            $this->request->getSession()->set('currentObject', $usersObject);
        }

        $referer = $this->request->headers->get('referer');
        return $this->redirect($referer ?: $this->generateUrl('home_index'));
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/users/dashboard', methods: ['GET'])]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        $leftToPay = $entityManager->getRepository(UsersObjectsServices::class)
            ->createQueryBuilder('users_objects_services')
            ->select('SUM(users_objects_services.total_price) AS total_price')
            ->innerJoin('users_objects_services.users_objects_services_bundles', 'users_objects_services_bundles')/** @see Invoices::$users_objects_services_bundles */
            ->leftJoin('users_objects_services_bundles.invoices', 'invoices')/** @see UsersObjectsServicesBundles::$invoices */
            ->andWhere('users_objects_services_bundles.users_object = :users_object')/** @see UsersObjectsServicesBundles::$users_object */
                ->setParameter('users_object', $this->request->getSession()->get('currentObject'))
            ->andWhere('invoices.is_paid IS NULL')
            ->getQuery()
            ->getSingleScalarResult()
        ;

        /** @var Invoices[] $invoices */
        $newInvoices = $entityManager->getRepository(Invoices::class)
            ->createQueryBuilder('invoices')
            ->innerJoin('invoices.users_objects_services_bundles', 'users_objects_services_bundles')/** @see Invoices::$users_objects_services_bundles */
            ->andWhere('users_objects_services_bundles.users_object = :users_object')/** @see UsersObjectsServicesBundles::$users_object */
            ->setParameter('users_object', $this->request->getSession()->get('currentObject'))
            ->addOrderBy('invoices.created_at', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;

        return $this->render('users/dashboard.twig', [
            'leftToPay' => $leftToPay,
            'newestInvoices' => reset($newInvoices),
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/users/invoices', 'users_invoices', methods: ['GET'])]
    public function invoices(EntityManagerInterface $entityManager): Response
    {
        /** @var Invoices[] $invoices */
        $invoices = $entityManager->getRepository(Invoices::class)
            ->createQueryBuilder('invoices')
            ->innerJoin('invoices.users_objects_services_bundles', 'users_objects_services_bundles')/** @see Invoices::$users_objects_services_bundles */
            ->andWhere('users_objects_services_bundles.users_object = :users_object')/** @see UsersObjectsServicesBundles::$users_object */
            ->setParameter('users_object', $this->request->getSession()->get('currentObject'))
            ->getQuery()
            ->getResult()
            ;

        return $this->render('users/invoices.twig', [
            'invoices' => $invoices,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/users/invoices/{id}',name: 'invoicesId', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function invoicesId(EntityManagerInterface $entityManager): Response
    {
        /** @var Invoices[] $invoice */
        $invoiceFound = $entityManager->getRepository(Invoices::class)
            ->createQueryBuilder('invoices')
            ->innerJoin('invoices.users_objects_services_bundles', 'users_objects_services_bundles')/** @see Invoices::$users_objects_services_bundles */
            ->andWhere('users_objects_services_bundles.users_object = :users_object')/** @see UsersObjectsServicesBundles::$users_object */
            ->setParameter('users_object', $this->request->getSession()->get('currentObject'))
            ->andWhere('invoices.id = :id')->setParameter('id', $this->request->get('id'))
            ->orderBy('invoices.created_at', 'DESC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult()
            ;
        if (!$invoiceFound) {
            return $this->redirect($this->generateUrl('users_invoices'));
        }

        return $this->render('users/invoice_view.twig', [
            'invoice' => reset($invoiceFound),
        ]);
    }
}
