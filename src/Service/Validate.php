<?php

namespace App\Service;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validate
{

    public function __construct(private ValidatorInterface $validator) {}

    /**
     * This funciton validate puloaded file
     */
    public function validate(UploadedFile $file)
    {
        $fileConstraints = new File([
            'maxSize' => '20M',
            'mimeTypes' => [
                'image/jpeg',
                'image/png',
            ],
            'mimeTypesMessage' => 'Please upload a valid JPEG or PNG image.',
        ]);

        return $this->validator->validate($file, $fileConstraints);
    }

    /**
     * This funciton return the error message of form filed validation
     */
    public function validationErrors($form): array
    {
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = [$error->getOrigin()->getName() => $error->getMessage()];
        }
        return ['message' => $errors, 'code' => Response::HTTP_BAD_REQUEST];
    }
}
