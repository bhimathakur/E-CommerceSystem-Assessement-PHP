<?php

namespace App\Controller;

use App\Entity\ContentManagement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContentType;
use App\Service\Content;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/admin/content', name: 'content_')]
class ContentManagementController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private Content $content
    ) {}

    #[Route('/list', name: 'list')]
    public function list()
    {
        $content = $this->em->getRepository(ContentManagement::class)->findAll();
        return $this->render('content/list.html.twig', [
            'pageContent' => $content
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(Request $request)
    {
        $content = new ContentManagement();
        $response = [];
        $form = $this->createForm(ContentType::class, $content);

        $response = '';
        if ($request->isMethod('POST')) {
            $productResponse = $this->content->create($form, $content, $request);

            if ($productResponse['code'] === Response::HTTP_CREATED) {
                return $this->redirectToRoute('content_list');
            }
            $response = $productResponse['message'];
        }

        return $this->render('content/create.html.twig', [
            'form' => $form,
            'response' => $response
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id)
    {
        $content = $this->em->getRepository(ContentManagement::class)->find($id);
        $form = $this->createForm(ContentType::class, $content, [
            'edit' => true
        ]);
        $response = '';
        if ($request->isMethod('POST')) {
            $response =  $this->content->update($request, $form);
            if ($response['code'] === Response::HTTP_CREATED) {
                return $this->redirectToRoute('content_list');
            }
            $response = $response['message'];
        }
        return $this->render('content/edit.html.twig', [
            'form' => $form,
            'response' => $response

        ]);
    }
}
