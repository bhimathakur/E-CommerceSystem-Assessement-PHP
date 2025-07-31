<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Enum\Status;
use App\Form\ProductEditType as FormProductEditType;
use App\Form\ProductType;
use App\Service\FileUploader;
use App\Service\Product as ServiceProduct;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\VarDumper\VarDumper;

#[Route('/admin/product', name: 'product')]
final class ProductController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private ServiceProduct $productService
    ) {}
    #[Route('/list', name: '_list')]
    public function index(): Response
    {
        $products = $this->em->getRepository(Product::class)->findAllProducts();
        return $this->render('product/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/add', name: '_add', methods: ['get', 'post'])]
    public function create(Request $request, FileUploader $fileUploader)
    {

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $response = '';
        if ($request->isMethod('POST')) {
            $productResponse = $this->productService->create($form, $product, $request, $fileUploader);
            if ($productResponse['code'] === Response::HTTP_CREATED) {
                return $this->redirectToRoute('product_list');
            }
            $response = $productResponse['message'];
            
        }   
        return $this->render('product/create.html.twig', [
            'form' => $form,
            'response' => $response

        ]);
    }


    #[Route('/edit/{id}', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id)
    {
        $product = $this->em->getRepository(Product::class)->find($id);

        $subCategory = $this->em->getRepository(Category::class)->findOneBy(['id' => $product->getSubCatId()]);
        $form = $this->createForm(FormProductEditType::class, $product, [
            'cat_id' => $id,
            'category' => $product->getCategory(),
            'sub_category' => $subCategory,
            'edit' => true
        ]);
        $response = '';
        if ($request->isMethod('POST')) {
            $response =  $this->productService->update($request, $form);
            if ($response['code'] === Response::HTTP_CREATED) {
                return $this->redirectToRoute('product_list');
            }
            $response = $response['message'];
        }
        return $this->render('product/edit.html.twig', [
            'form' => $form,
            'response' => $response

        ]);
    }
}
