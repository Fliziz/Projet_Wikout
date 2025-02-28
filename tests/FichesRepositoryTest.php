<?php

namespace App\Tests;


use App\Repository\FichesRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class FichesRepositoryTest extends WebTestCase
{
    public function testFonctionFindBytitle(): void
    {
        //Un mock est un objet qui simule le comportement d'un objet, en l'ocurence ici le repository
        $fichesRepository = $this->createMock(FichesRepository::class); 
        $fichesRepository->expects($this->once()) // once() signifie que la méthode doit être appelée une fois
            ->method('findByTitleOrCategoryOrMusclesOrDifficulte')
            ->with('test', 1, 1, 1)
            ->willReturn([]);

        $this->assertIsArray($fichesRepository->findByTitleOrCategoryOrMusclesOrDifficulte('test', 1, 1, 1));

    }
}
