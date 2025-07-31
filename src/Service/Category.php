<?php

namespace App\Service;

use App\Entity\Category as EntityCategory;
use App\Repository\CategoryRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Category
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryRepository $categoryRepo,
        private RouterInterface $router,
        private FormFactoryInterface $formFactory,
        private FileUploader $fileUploader,
        private Validate $validate

    ) {}

    /**
     * This funciton return the categories as array
     */
    public function getCategoriesList(): array
    {
        $categories = $this->em->getRepository(EntityCategory::class)->findAll();
        $categoriesList = [];
        foreach ($categories as $category) {
            $parentCategoryId = $category->getSubCatId();
            if ($parentCategoryId !== 0) {
                $parentCategory = $this->getParentCategory($categories, $parentCategoryId);
            } else {
                $parentCategory = 'None';
            }
            $categoriesList[] = [
                'id' => $category->getId(),
                'category' => $category->getTitle(),
                'parent_category' => $parentCategory,
                'image' => $category->getImage(),
                'description' => $category->getDescription(),
                'status' => $category->getStatus()->value

            ];
        }
        return $categoriesList;
    }


    /**
     * This function return subcategory list based on category id
     */
    public function getSubcategoryList(int $id): array
    {
        $categories = $this->em->getRepository(EntityCategory::class)->findBy(
            ['sub_cat_id' => $id]
        );

        $data = [];
        if (count($categories) == 0) {
            $data = [['id' => '', 'title' => 'No subcategory']];
        }
        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'title' => $category->getTitle(),
            ];
        }
        return $data;
    }
    /**
     * This function return the subcategory parent category.
     */
    private function getParentCategory($categories, int $id): string
    {
        foreach ($categories as $category) {
            if ($category->getId() === $id) {
                return $category->getTitle();
            }
        }
        return 'Category does not exist';
    }


    /**
     * This function add category into database
     */
    public function create(Request $request, $form, $categoryEntity)
    {

        if ($form->isSubmitted() && $form->isValid()) {

            $fileName = $this->fileUploader->upload($request->files->get('category')['image']);
            if (is_array($fileName)) {
                return $fileName;
            }
            $categoryEntity->setImage($fileName);
            $categoryEntity->setStatus(Status::ACTIVE);
            $categoryEntity->setCreatedAt(new DateTime());
            if ($categoryEntity->getSubCatId() === null) {
                $categoryEntity->setSubCatId(0);
            }
            $reuslt = $this->categoryRepo->save($categoryEntity);
            if (is_array($reuslt)) {
                return $reuslt;
            }
            return ['message' => 'Category created successfully', 'code' => Response::HTTP_CREATED];
        } else {
            return $this->validate->validationErrors($form);
        }
    }


    public function update(Request $request, FormInterface $form)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $fileName = $this->fileUploader->upload($request->files->get('category')['image']);
            if (is_array($fileName)) {
                return $fileName;
            }

            $data = $form->getData();
            if ($fileName !== '') {
                $data->setImage($fileName);
            }
            $reuslt = $this->categoryRepo->save($data);

            if (is_array($reuslt)) {
                return $reuslt;
            }
            return ['message' => 'Category updated successfully', 'code' => Response::HTTP_CREATED];
        } else {
            return $this->validate->validationErrors($form);
        }
    }
}
