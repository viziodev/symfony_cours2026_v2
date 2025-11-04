<?php 
namespace App\Service\Impl;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploaderService 
{
    public function __construct(private readonly string $uploadDir,private readonly  SluggerInterface $slugger)
    {
        
    }
    public function upload(UploadedFile $file):string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);//nom du fichier sans extension
        $safeFilename =$this->slugger->slug($originalFilename);//rendre le nom sur et compatible URL(enlever les espaces et caracteres speciaux qu'on remplace par des - )
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        // Move the file to the directory where brochures are stored
        try {
            $file->move($this->uploadDir, $newFilename);
        } catch (FileException $e) {
            throw new \Exception("Erreur lors du telechargement du fichier");
        }

        return $newFilename;
    }

    
}