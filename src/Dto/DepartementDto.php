<?php 
namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class DepartementDto
{
 public int $id ;
#[Assert\Length(
                min: 2,
                max: 100,
                minMessage:'Le nom doit contenir au moins {{limit}} caractere',
                maxMessage:'Le nom doit contenir au plus {{limit}} caractere')]
#[Assert\NotBlank(message:'Le nom est obligatoire')]
 public string $name ;
}