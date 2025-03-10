<?php

namespace App\Controller;

use App\Entity\Services;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class ServicesController extends BaseController
{
    #[Route('/services/{id}', name: 'services_view', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function view(EntityManagerInterface $entityManager, Services $service): Response
    {
        return $this->render('services/view.twig', [
            //'controller_name' => 'ServicesController',
            'service' => $service,
        ]);
    }

    #[Route('/services/my', methods: ['GET'])]
    public function my(EntityManagerInterface $entityManager): Response
    {
        return $this->render('services/my.twig', [
            //
        ]);
    }
}
