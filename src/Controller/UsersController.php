<?php

namespace App\Controller;

use App\Entity\Invoices;
use App\Entity\Questions;
use App\Entity\QuestionsAnswers;
use App\Entity\Users;
use App\Entity\UsersObjects;
use App\Entity\UsersObjectsServices;
use App\Entity\UsersObjectsServicesBundles;
use App\Forms\UserProfileForm;
use App\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
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
            ->addOrderBy('invoices.period_start', 'DESC')/** @see Invoices::$period_start */
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
            ->addOrderBy('invoices.period_start', 'DESC')/** @see Invoices::$period_start */
            ->getQuery()
            ->getResult()
            ;

        return $this->render('users/invoices.twig', [
            'invoices' => $invoices,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/users/invoices/{id}',name: 'invoicesId', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function invoicesId(
        EntityManagerInterface $entityManager,
    ): Response
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


    //region questions
    #[IsGranted('ROLE_USER')]
    #[Route('/users/my_questions', 'my_questions', methods: ['GET'])]
    public function my_questions(EntityManagerInterface $entityManager): Response
    {
        /** @var Questions[] $questions */
        $questions = $entityManager->getRepository(Questions::class)
            ->createQueryBuilder('questions')
            //->innerJoin('questions.questions', 'questions')/** @see QuestionsAnswers::$questions */
            ->andWhere('questions.users = :users')/** @see Questions::$users */
            ->setParameter('users', $this->getUser())
            ->addOrderBy('questions.id', 'DESC')/** @see QuestionsAnswers::$id */
            ->getQuery()
            ->getResult()
        ;

        return $this->render('users/my_questions.twig', [
            'questions' => $questions,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/users/my_questions/{id}', 'my_questions_delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    public function my_questions_delete(
        EntityManagerInterface $entityManager,
        #[MapEntity(id: 'id')] ?Questions $questions
    ): Response
    {
        if (
            $questions
            && $questions->users
            && $questions->users->getId()
            && $questions->users->getId() === $this->getUser()->getId()
        ) {
            Registry::getDoctrineManager()->remove($questions);
            Registry::getDoctrineManager()->flush();
            //$questions = $entityManager->getRepository(Questions::class)
            //    ->createQueryBuilder('questions')
            //    ->andWhere('questions.users = :users')/** @see Questions::$users */
            //    ->setParameter('users', $this->getUser())
            //    ->andWhere('questions.id = :users')/** @see Questions::$users */
            //    ->setParameter('users', $this->getUser())
            //    ->delete()
            //;
            //dvd($questions);
            $this->addFlash("success", 'Klausimas ištrintas');
        }

        $data = [
            'redirect' => $this->generateUrl('my_questions'),
        ];
        return $this->json($data);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/users/my_questions/{id}', 'my_questions_save', methods: ['PUT'], requirements: ['id' => Requirement::DIGITS])]
    public function my_questions_save(
        EntityManagerInterface $entityManager,
        #[MapEntity(id: 'id')] Questions $questions,
    ): Response
    {
        if (
            $questions
            && $questions->users
            && $questions->users->getId()
            && $questions->users->getId() === $this->getUser()->getId()
        ) {
            $questions->question = $this->request->get('question');
            Registry::getDoctrineManager()->persist($questions);
            Registry::getDoctrineManager()->flush();
            //$this->addFlash("success", 'Klausimas atnaujintas');
        }

        $data = [
            'success' => 'ok',
        ];
        return $this->json($data);
    }
    //endregion questions


    //region profile
    #[IsGranted('ROLE_USER')]
    #[Route('/users/profile', 'users_profile', methods: ['GET', 'PUT'])]
    public function profile(
        EntityManagerInterface $entityManager,
    ): Response
    {
        $form = $this->createForm(UserProfileForm::class, $this->getUser(), [
            'action' => '/users/profile',
            'method' => 'PUT',
        ]);

        $form->handleRequest($this->request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $data = $form->getData();

                /** @var Users|null $user */
                $user = $this->getUser();

                $user->email = $form->get('email')->getData();
                $user->phone = $form->get('phone')->getData();
                $this->managerRegistry->getManager()->persist($user);
                $this->managerRegistry->getManager()->flush();

                $this->addFlash("success", 'Išsaugota');

                return $this->redirectToRoute('users_profile');
            } else {
                return $this->redirectToRoute('users_profile');
            }
        }

        return $this->render('users/profile.twig', [
            'user' => $user = $this->getUser(),
            'profile_form' => $form->createView(),
        ]);
    }
    //endregion profile
}
