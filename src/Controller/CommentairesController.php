<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Articles;
use App\Entity\Fiches;
use App\Repository\FicheContenuRepository;
use App\Repository\CommentairesRepository;
use App\Form\CommentairesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;


final class CommentairesController extends AbstractController
{   
    
    #[Route('commentaire/index', name: 'commentaires_index')]
    public function index(CommentairesRepository $commentairesRepository): Response
    {
        $commentaires = $commentairesRepository->findAll();

        return $this->render('commentaires/index.html.twig', [
            'commentaires' => $commentaires
        ]);
    }

    #[Route('fiches/{id}/commentaire/new',name: 'commentaire_new', methods: ['GET', 'POST'])]
    public function new(CsrfTokenManagerInterface $csrfTokenManager,Request $request, EntityManagerInterface $em, Fiches $fiche, FicheContenuRepository $ficheContenuRepository): Response
    {   
        
        $FicheContenu = $ficheContenuRepository->findOneBy(['Fiche' => $fiche]);
        
        $csrfToken = $csrfTokenManager->getToken('fiches_edit')->getValue();

        if($request->isMethod('POST')){

            // Récupérer et vérifier le token CSRF
            $token = $request->request->get('_csrf_token');
            
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('fiches_edit', $token))) {

                throw new AccessDeniedHttpException('Le token CSRF est invalide.');
            }

            $commentaire = new Commentaires(); // On crée une nouvelle instance de l'entité commentaires

            // On récupère les données soumises dans le formulaire et on les attribue à l'entité $commentaires
            // $commentaires->setIdArticle($request->request->get('article'));

            $commentaire->setUtilisateur($this->getUser()); // GetUser() Recupere l'utilisateur actuel
            $commentaire->setFicheContenu($FicheContenu);
            $commentaire->setContenu($request->request->get('contenu')); // Attribue le contunu depuis la requête        
            $commentaire->setDate(new \DateTime());

            $em->persist($commentaire); // Prépare l'entité $commentaires à être sauvegardée dans la base de données
            $em->flush(); // Sauvegarde réellement les données dans la base de données
            
            return $this->redirectToRoute('fiche_show', [
                'id'=> $fiche->getId()
            ]);
        }
        
        return $this->render('commentaires/new.html.twig', [
            "fiche" => $fiche,
            'csrf_token' => $csrfToken // Passe le token CSRF à la vue pour l'utiliser dans le formulaire
    ]);
    }


    #[Route('fiches/{id}/commentaire/edit/{com}', name: 'commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(CsrfTokenManagerInterface $csrfTokenManager,$com,Request $request,CommentairesRepository $commentairesRepository, EntityManagerInterface $em,Fiches $Fiche): Response
    {
       
        // $commentairess = $commentairesRepository -> findAll();
        $commentaire = $commentairesRepository->find($com);
        
        $csrfToken = $csrfTokenManager->getToken('fiches_edit')->getValue();
        
        if($request->isMethod('POST')){
            
            // Récupérer et vérifier le token CSRF
            $token = $request->request->get('_csrf_token');
            
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('fiches_edit', $token))) {

                throw new AccessDeniedHttpException('Le token CSRF est invalide.');
            }

            // On récupère les données soumises dans le formulaire et on les attribue à l'entité $commentaires
            // $commentaires->setIdArticle($request->request->get('article'));

            $commentaire->setContenu($request->request->get('contenu')); // Attribue le contunu depuis la requête        
            
            $em->persist($commentaire); // Prépare l'entité $commentaires à être sauvegardée dans la base de données
            $em->flush(); // Sauvegarde réellement les données dans la base de données
            
            return $this->redirectToRoute('fiche_show', [
                'id'=> $Fiche->getId()
            ]);
        }

        return $this->render('commentaires/edit.html.twig', [
            'Fiche' => $Fiche,
            'commentaire' => $commentaire,
            'csrf_token' => $csrfToken // Passe le token CSRF à la vue pour l'utiliser dans le formulaire
        ]);
    }

    #[Route('fiches/{Fiche}/commentaires/delete/{id}',name: 'commentaire_delete', methods: ['POST'])]
    public function delete($Fiche,Commentaires $commentaires , EntityManagerInterface $entityManager): Response 
    {      
            $entityManager->remove($commentaires);
            $entityManager->flush();   

            return $this->redirectToRoute('fiche_show', [
                'id'=> $Fiche
            ], Response::HTTP_SEE_OTHER);

    }

    #[Route('admin/api/commentaire/index',name: 'api_commentaires_index', methods: ['GET'])]
    public function index_api(CommentairesRepository $commentairesRepository): JsonResponse
    {
        $commentaires = $commentairesRepository->findAll();

        $data = [];
        foreach ($commentaires as $commentaire) {
            $data[] = [
                'id' => $commentaire->getId(),
                'utilisateur' => $commentaire->getUtilisateur()->getEmail(), // Assurez-vous que getEmail() existe
                'contenu' => $commentaire->getContenu(),
                'date' => $commentaire->getDate()->format('Y-m-d')
            ];
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    #[Route('admin/api/commentaire/delete/{id}',name: 'api_commentaire_delete', methods: ['DELETE'])]
    public function delete_api(Commentaires $commentaires , EntityManagerInterface $entityManager): JsonResponse 
     {       

             $entityManager->remove($commentaires);
             $entityManager->flush();   

             return new JsonResponse(['status' => 'User deleted'], JsonResponse::HTTP_OK);
     }
}

