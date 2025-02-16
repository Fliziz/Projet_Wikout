<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/admin/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    public function index_api(ContactRepository $contactsRepository): JsonResponse
    {
        $contacts = $contactsRepository->findAll();

        $data = [];
        foreach ($contacts as $contact) {
            $data[] = [
                'id' => $contact->getId(),
                'prenom' => $contact->getPrenom(), // Assurez-vous que getEmail() existe
                'nom' => $contact->getNom(),
                'email' => $contact->getEmail(),
                'description' => $contact->getDescription(),
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }
}
