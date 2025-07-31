<?php

namespace App\Controller;

use App\Entity\Category as EntityCategory;
use App\Form\CategoryType;
use App\Service\Category;
use App\Service\Status;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\VarDumper\VarDumper;

/**
 * This class manage the category add/edit/delete functinality
 */
#[Route('/admin/category', name: 'category_')]
class CategoryController extends AbstractController
{
    public function __construct(
        private Category $category,
        private EntityManagerInterface $em
    ) {}


    /**
     * This function render the category add page and add category into the database
     */
    #[Route('/add', name: 'add', methods: ['get', 'post'])]
    public function create(Request $request)
    {
        $category = new EntityCategory();
        $form = $this->createForm(CategoryType::class, $category);
        $response = "";
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $catResponse = $this->category->create($request, $form, $category);

            if ($catResponse['code'] === Response::HTTP_CREATED) {
                return $this->redirectToRoute('category_list');
            }
            $response = $catResponse['message'];
        }

        return $this->render(
            'category/create.html.twig',
            [
                'form' => $form->createView(),
                'categories' => $this->getCategories(),
                'response' => $response
            ]
        );
    }

    /**
     * This function used dispaly list of all the categories
     */
    #[Route('/list', name: 'list')]
    public function list()
    {
        //$categories = $this->em->getRepository(EntityCategory::class)->findAll();

        $categoriesList = $this->category->getCategoriesList();
        return $this->render('category/list.html.twig', [
            'categories' => $categoriesList

        ]);
    }



    /**
     * This function returns categories list
     */
    private function getCategories(): array
    {
        return $this->em->getRepository(EntityCategory::class)->findBy(['sub_cat_id' => 0, 'status' => Status::ACTIVE]);
    }

    /**
     * Render the edit category form and save the changes into the database.
     */
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit($id, Request $request)
    {
        $category = $this->em->getRepository(EntityCategory::class)->find($id);

        $form = $this->createForm(CategoryType::class, $category, ['edit' => true]);
        $response = '';
        if ($request->isMethod('POST')) {
            $response =  $this->category->update($request, $form);
            if ($response['code'] === Response::HTTP_CREATED) {
                return $this->redirectToRoute('category_list');
            }
            $response = $response['message'];
        }
        return $this->render('category/edit.html.twig', [
            'form' => $form,
            'categories' => $this->getCategories(),
            'response' => $response
        ]);
    }


    /**
     * This funciton return the sub category list based on the passed category id. 
     */
    #[Route('get_sub_category/{id}', name: 'sub_category')]
    public function getSubCategory($id)
    {
        $categories = $this->category->getSubcategoryList($id);
        return $this->json($categories, 200);
    }
}
