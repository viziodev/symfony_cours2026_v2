<?php 
namespace App\Service\Impl;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader 
{
     public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ) {
    }
    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->targetDirectory, $newFilename);
        } catch (FileException $e) {
            // Gérer l'exception si quelque chose se passe mal lors du déplacement du fichier
            throw new \Exception("Erreur lors de l'upload du fichier.");
        }

        return $newFilename;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}