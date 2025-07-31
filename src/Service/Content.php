<?php

namespace App\Service;

use App\Entity\ContentManagement;
use App\Entity\Product as EntityProduct;
use App\Enum\Status;
use App\Repository\ContentManagementRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;

class Content
{
    public function __construct(
        private ContentManagementRepository $contentRepo,
        private FileUploader $fileUploader,
        private Validate $validate

    ) {}


    public function create(FormInterface $form, ContentManagement $contentEntity, Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileName = $this->fileUploader->upload($request->files->get('content')['image']);
            if (is_array($fileName)) {
                return $fileName;
            }
            $contentEntity->setImage($fileName);

            $reuslt = $this->contentRepo->save($contentEntity);
            if (is_array($reuslt)) {
                return $reuslt;
            }
            return ['message' => 'Content created successfully', 'code' => Response::HTTP_CREATED];
        } else {
            return $this->validate->validationErrors($form);
        }
    }


    public function update(Request $request, FormInterface $form)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('content')['image'];
            $data = $form->getData();
            if ($file !== null) {
                $fileName = $this->fileUploader->upload($file);
                if (is_array($fileName)) {
                    return $fileName;
                }
                if ($fileName !== '') {
                    $data->setImage($fileName);
                }
            }
            $reuslt = $this->contentRepo->save($data);

            if (is_array($reuslt)) {
                return $reuslt;
            }
            return ['message' => 'Content updated successfully', 'code' => Response::HTTP_CREATED];
        } else {
            return $this->validate->validationErrors($form);
        }
    }
}
