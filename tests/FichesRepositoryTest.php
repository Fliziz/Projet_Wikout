<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Repository\FichesRepository;

class FichesRepositoryTest extends TestCase
{
    public function testRecherche(): void
    {
        $FichesRepo = new FichesRepository;
        
        $this->assertIsArray($FichesRepo->findByTitleOrCategoryOrMusclesOrDifficulte(";'Select * From fiches ","", "",""));
    }
}
