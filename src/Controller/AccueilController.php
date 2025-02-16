<?php

namespace App\Controller;

use App\Repository\FichesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('', name: 'accueil')]
    public function index(FichesRepository $fichesRepository): Response
    {
        $fiches = $fichesRepository->findAll();

        return $this->render('accueil/Accueil.html.twig', [
            'fiches' => $fiches,
        ]);
    }
}
