<?php

namespace App\Controller;

use App\Entity\Services;
use App\Service\StorageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    #[Route(path: '/', name: 'home_index')]
    public function home_index(EntityManagerInterface $entityManager): Response
    {
        $services = $entityManager->getRepository(Services::class)->getActiveServices();

        return $this->render('general/index.twig', [
            'services' => $services,
        ]);
    }

    #[Route(path: '/assets/{assetType}/{fileName}', name: 'assets')]
    public function assets(string $assetType, string $fileName, StorageService $storageService)
    {
        return new Response('not found!', 404);
    }
}
