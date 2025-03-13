<?php

namespace App\Controller;

use App\Entity\Services;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

final class ServicesController extends BaseController
{
    #[Route('/services', name: 'services_list', methods: ['GET'])]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $services = $entityManager->getRepository(Services::class)->getActiveServices();

        return $this->render('services/list.twig', [
            'services' => $services,
        ]);
    }
    #[Route('/services/{id}', name: 'services_view', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function view(EntityManagerInterface $entityManager, Services $service): Response
    {
        return $this->render('services/view.twig', [
            //'controller_name' => 'ServicesController',
            'service' => $service,
        ]);
    }
}
