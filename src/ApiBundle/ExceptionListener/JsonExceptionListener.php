<?php

namespace ApiBundle\ExceptionListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonExceptionListener
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $data = [];
        if ($exception instanceof HttpException) {
            $data['error']['code'] = $exception->getStatusCode();
        } else {
            $data['error']['code'] = $exception->getCode();
        }
        $data['error']['message'] = $exception->getMessage();

        $env = $this->container->getParameter('kernel.environment');
        if ("dev" == $env) {
            $data['error']['exception'] = $exception->getTrace();
        }

        $response = new JsonResponse($data);
        $event->setResponse($response);
    }
}
