<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\VarDumper\VarDumper;
use Twig\Environment;

class ExceptionListener
{
    public function __construct(private Environment $twig) {}
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $content = $this->twig->render('error/error404.html.twig', [
            'exception' => $exception,
        ]);

        $response = new Response();
        $response->setContent($content);
        $response->headers->set('Content-Type', 'text/html; charset=utf-8');
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
