<?php 
namespace App\Services\Impl;
use App\Services\UploadPhotoService;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class UploadPhotoServiceImpl implements UploadPhotoService{
         
                
                public function uploadPhoto(string $uploadDirectory,UploadedFile $file): string
                {
                        // Génère un nom unique pour le fichier
                         $newFilename = uniqid('photo_', true) . '.' . $file->guessExtension();
                          // Déplace le fichier dans le dossier d’upload
                         $file->move($uploadDirectory, $newFilename);
                         return $newFilename;
                }
}