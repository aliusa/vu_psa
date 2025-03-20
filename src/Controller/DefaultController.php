<?php

namespace App\Controller;

use App\Commands\GenerateInvoiceCommand;
use App\Commands\ResetPaymentscommand;
use App\Entity\Services;
use App\Service\StorageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
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

    #[Route(path: '/clear')]
    public function clear(KernelInterface $kernel)
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => ResetPaymentscommand::$defaultName,
            // (optional) define the value of command arguments
            //'fooArgument' => 'barValue',
            // (optional) pass options to the command
            //'--bar' => 'fooValue',
            // (optional) pass options without value
            //'--baz' => true,
        ]);

        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();
        //dvd($content);

        // return new Response(""), if you used NullOutput()
        return new Response('ok');
    }
}
