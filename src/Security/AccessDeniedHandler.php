<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Twig\Environment;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{

    public function __construct(private Environment $twig)
    {
        
    }
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
     
        $response = new Response();
        $response->setContent($this->twig->render('error/access_denied.html.twig'));
        $response->setStatusCode(Response::HTTP_FORBIDDEN);
        return $response;
//        return new Response('Access 2denied, You are not fully authenticated to access this record or page', Response::HTTP_FORBIDDEN);
    }
}