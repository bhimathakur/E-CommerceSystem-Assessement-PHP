<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * This class used to upload file 
 */
class FileUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
        private Validate $validate
    ) {}

    /**
     * This function upload file and return file name if file uploaded succefully
     * else return exception custom error message
     */
    public function upload($file): string | array
    {
        try {

            $validateFile = $this->validateFile($file);
            if ($validateFile !== true) {
                return $validateFile;
            }
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $this->slugger->slug($originalFileName);
            $fileName = $safeFileName . '-' . uniqid() . '.' . $file->guessExtension();
            $file->move($this->getDirectory(), $fileName);
            return $fileName;
        } catch (FileException $e) {
            return ['message' => $e->getMessage(), 'code' => $e->getCode()];
        }
    }


    private function validateFile($image)
    {
        if ($image === null) {
            return '';
        }

        $voilation = $this->validateImage($image);
        if (is_array($voilation)) {
            return $voilation;
        }
        return true;
    }



    /**
     * This funciton validate image. (This function cab moved in centerlized file where can be used reused)
     */
    private function validateImage($image): bool | array
    {
        $voilations  = $this->validate->validate($image);
        if (count($voilations) > 0) {
            $errors = [];
            foreach ($voilations as $voilation) {
                $errors[] = ['image' => $voilation->getMessage()];
            }
            return ['message' => $errors, 'code' => Response::HTTP_BAD_REQUEST];
        }
        return true;
    }



    /**
     * This function return directory path where file need to store
     */
    private function getDirectory(): string
    {

        return $this->targetDirectory;
    }
}
