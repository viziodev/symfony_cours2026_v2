<?php 
namespace App\DTO   ;
use App\Entity\Employe;
use \DateTimeImmutable;
class EmployeListDto 
{
    public int $id;
    public string $numero;
    public string $nomComplet;
    public string $telephone;           
    public bool $isArchived ;
    public DateTimeImmutable $embaucheAt ; 
    public string  $departementName ; 
    public ?string $photoUrl = null;

    //Mappers 
    public static function fromEntitie(Employe $entity): EmployeListDto
    {
        $dto = new EmployeListDto();
        $dto->id = $entity->getId(); 
        $dto->numero = $entity->getNumero();
        $dto->nomComplet = $entity->getNomComplet();
        $dto->telephone = $entity->getTelephone();
        $dto->isArchived = $entity->isArchived();
        $dto->embaucheAt = $entity->getEmbaucheAt();
        $dto->departementName = $entity->getDepartement() ? $entity->getDepartement()->getName() : 'N/A';
        $dto->photoUrl = $entity->getPhoto() ? '/uploads/employes/' . $entity->getPhoto() : null;
        return $dto;    
    }

    public static function fromEntities(array $entities): array
    {
         return array_map(function(Employe $entity){
            return self::fromEntitie($entity);
         }, $entities);
       
    }

}