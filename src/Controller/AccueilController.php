<?php

namespace App\Controller;

use App\Repository\FichesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\MongoDBService;

class AccueilController extends AbstractController
{
    #[Route('', name: 'accueil')]
    public function index(FichesRepository $fichesRepository, MongoDBService $mongoDBService): Response
    {
        $fiches = $fichesRepository->findByIdDesc();

        $mongoDBService->inserVisit('accueil');

        return $this->render('accueil/Accueil.html.twig', [
            'fiches' => $fiches,
        ]);
    }

    #[Route('/ConditionUtilisation', name: 'condition_utilisation')]
    public function condition(): Response
    {
        return $this->render('accueil/ConditionUtilisation.html.twig');
    }
}
