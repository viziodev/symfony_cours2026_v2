<?php 
namespace App\Services;
use Symfony\Component\HttpFoundation\File\UploadedFile;
interface UploadPhotoService{
    public function uploadPhoto(string $uploadDirectory,UploadedFile $file): string;
}