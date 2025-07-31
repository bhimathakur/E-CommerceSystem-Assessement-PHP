<?php

namespace App\Service;

use App\Entity\Product as EntityProduct;
use App\Enum\Status;
use App\Repository\ProductRepository;
use DateTime;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

class Product
{
    public function __construct(
        private ProductRepository $productRepo,
        private FileUploader $fileUploader,
        private Validate $validate
    ) {}


    /**
     * This function validate the product record and insert into database.
     */
    public function create(FormInterface $form, EntityProduct $entityProduct, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fileName = $this->fileUploader->upload($request->files->get('product')['image']);
            if (is_array($fileName)) {
                return $fileName;
            }
            $entityProduct->setImage($fileName);
            $entityProduct->setStatus(Status::ACTIVE);
            $entityProduct->setCreatedAt(new DateTime());
            $reuslt = $this->productRepo->save($entityProduct);
            if (is_array($reuslt)) {
                return $reuslt;
            }
            return ['message' => 'Product created successfully', 'code' => Response::HTTP_CREATED];
        } else {
            return  $this->validate->validationErrors($form);
        }
    }


    /**
     * This function validate the product record and insert into database.
     */
    public function update(Request $request, FormInterface $form)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fileName = $this->fileUploader->upload($request->files->get('product_edit')['image']);
            if (is_array($fileName)) {
                return $fileName;
            }

            $data = $form->getData();
            if ($fileName !== '') {
                $data->setImage($fileName);
            }
            $data->setSubCatId($data->getSubCatId());
            $reuslt = $this->productRepo->save($data);

            if (is_array($reuslt)) {
                return $reuslt;
            }
            return ['message' => 'Category updated successfully', 'code' => Response::HTTP_CREATED];
        } else {
            return $this->validate->validationErrors($form);
        }
    }
}
