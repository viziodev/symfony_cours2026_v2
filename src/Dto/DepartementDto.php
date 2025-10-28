<?php 
namespace App\Dto;

use App\Repository\DepartementRepository;
use Symfony\Component\Validator\Constraints as Assert;

class DepartementDto
{
  public int $id;
  #[Assert\NotBlank(message: 'Le nom est obligatoire')]
  #[Assert\Length( 
        min: 2,
        max: 100,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le nom doit contenir au plus {{ limit }} caractères'
    )]
  public string $name;
  public int $nbreEmployes=0;
  public ?\DateTimeImmutable $createAt=null;
  public ?bool $archived=null;
  public function __construct(
        private ?DepartementRepository $departementRepository = null
    ) {}


}