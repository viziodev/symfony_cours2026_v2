<?php 
namespace App\Service\Impl;

use App\Service\GenerateNumeroService;

class GenerateNumeroServiceImpl  implements GenerateNumeroService
{
    public function generateNumeroCompte():string
    {
        $numero="COMPT-".strtoupper(bin2hex(random_bytes(4)));
        return $numero;
    }
}