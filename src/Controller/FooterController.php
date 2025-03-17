<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FooterController extends AbstractController
{
    #[Route('/ConditionUtilisation', name: 'condition_utilisation')]
    public function affichage_utilisation(CsrfTokenManagerInterface $csrfTokenManager): Response
    {

        $csrfToken = $csrfTokenManager->getToken('contact')->getValue();

        return $this->render('footer/ConditionUtilisation.html.twig', [
            'csrf_token' => $csrfToken, // Passe le token CSRF à la vue
        ]);
    }

    #[Route('/ConfidentialiteEtCookies', name: 'confidentialite_cookies')]
    public function affichage_cookies(CsrfTokenManagerInterface $csrfTokenManager): Response
    {

        $csrfToken = $csrfTokenManager->getToken('contact')->getValue();

        return $this->render('footer/ConfidentialiteEtCookies.html.twig', [
            'csrf_token' => $csrfToken, // Passe le token CSRF à la vue
        ]);
    }
}
