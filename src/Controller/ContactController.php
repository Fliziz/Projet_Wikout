<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ContactController extends AbstractController
{
    #[Route('/admin/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    #[Route('/contact',name: 'contact_new', methods: ['GET','POST'])]
    public function new(CsrfTokenManagerInterface $csrfTokenManager,Request $request, EntityManagerInterface $em): Response
    {   

        if($request->isMethod('POST')){
            // Récupérer et vérifier le token CSRF
            $token = $request->request->get('_csrf_token');
            
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('contact', $token))) {

                throw new AccessDeniedHttpException('Le token CSRF est invalide.');
            }

            $contact = new Contact(); 


            $contact->setPrenom($request->request->get('prenom')); 
            $contact->setNom($request->request->get('nom'));
            $contact->setEmail($request->request->get('email'));       
            $contact->setDescription($request->request->get('description'));

            $em->persist($contact); 
            $em->flush(); 
            
             return $this->render('contact/success.html.twig');
        }

        
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
