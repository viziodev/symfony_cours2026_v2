<?php 
namespace App\Services\Impl;
use App\Services\GenerateNumero;
class GenerateNumeroImpl implements GenerateNumero{
    public function generate(): string{
        $number = random_int(10000, 99999);
        return 'EMP-' . $number;
    }
}