<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ExceptionListener
{

    private $isDev = false;

    public function __construct(KernelInterface $kernel)
    {
        $this->isDev = $kernel->getEnvironment() === 'dev';
    }

    public function onKernelException(ExceptionEvent $event)
    {
        if(preg_match('/^\/api/', $event->getRequest()->getPathInfo())){
            // You get the exception object from the received event
            $exception = $event->getThrowable();
            $message = [
                'error' => $exception->getMessage()
            ];

            if($this->isDev){
                $message['trace'] = $exception->getTrace();
            }


            // Customize your response object to display the exception details
            $response = new JsonResponse($message);

            // HttpExceptionInterface is a special type of exception that
            // holds status code and header details
            if ($exception instanceof HttpExceptionInterface) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
            } else {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $response->headers->set('Content-Type', 'application/json');

            // sends the modified response object to the event
            $event->setResponse($response);
        }
    }
}
