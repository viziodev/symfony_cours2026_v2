<?php 
namespace App\DTO   ;

use App\Entity\Departement;
use \DateTimeImmutable;
class DepartementListDto 
{
    public int $id;
    public string $name;
    public bool $isArchived ;
    public int  $nbreEmploye = 0;
    public DateTimeImmutable $createAt ;


    //Mappers 
    public static function fromEntitie(Departement $entity): DepartementListDto
    {
        $dto = new DepartementListDto();
        $dto->id = $entity->getId();
        $dto->name = $entity->getName();
        $dto->isArchived = $entity->isArchived();
        $dto->createAt = $entity->getCreateAt();
        $dto->nbreEmploye = count($entity->getEmployes());
        return $dto;
    }
    public static function fromEntities(array $entities): array
    {
         return array_map(function(Departement $entity){
            return self::fromEntitie($entity);
         }, $entities);
       
    }
}