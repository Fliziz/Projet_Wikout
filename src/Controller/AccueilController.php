<?php

namespace App\Controller;

use App\Service\MongoDBService;
use App\Repository\FichesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('', name: 'accueil')]
    public function index(CsrfTokenManagerInterface $csrfTokenManager,FichesRepository $fichesRepository, MongoDBService $mongoDBService): Response
    {
        $csrfToken = $csrfTokenManager->getToken('contact')->getValue();

        $fiches = $fichesRepository->findByIdDesc();

        $mongoDBService->inserVisit('accueil');

        return $this->render('accueil/Accueil.html.twig', [
            'fiches' => $fiches,
            'csrf_token' => $csrfToken,
        ]);
    }

}
