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


final class CommentairesController extends AbstractController
{
    #[Route('commentaire/index',name: 'commentaires_index', methods: ['GET'])]
    public function index(CommentairesRepository $commentairesRepository): Response
    {
        $commentaires = $commentairesRepository->findAll();

        return $this->render('commentaires/index.html.twig', [
            'commentaires' => $commentaires,
        ]);

    }

    #[Route('fiches/{id}/commentaire/new',name: 'commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em, Fiches $fiche, FicheContenuRepository $ficheContenuRepository): Response
    {   
        
        $FicheContenu = $ficheContenuRepository->findOneBy(['Fiche' => $fiche]);
        

        if($request->isMethod('POST')){

            $commentaire = new Commentaires(); // On crée une nouvelle instance de l'entité commentaires

            // On récupère les données soumises dans le formulaire et on les attribue à l'entité $commentaires
            // $commentaires->setIdArticle($request->request->get('article'));

            $commentaire->setUtilisateur(app.user); // Attribue le titre depuis la requête
            $commentaire->setFicheContenu($FicheContenu);
            $commentaire->setContenu($request->request->get('Contenu')); // Attribue le contunu depuis la requête        
            $commentaire->setDate($request->request->get('Date'));

            $em->persist($commentaire); // Prépare l'entité $commentaires à être sauvegardée dans la base de données
            $em->flush(); // Sauvegarde réellement les données dans la base de données
            
            return $this->redirectToRoute('fiche_show', [
                'id'=> $article->getId()
            ]);
        }
        
        return $this->render('commentaires/new.html.twig', ["fiche" => $fiche]);
    }


    #[Route('{id}/edit/{com}', name: 'commentaire_edit', methods: ['GET', 'POST'])]
    public function edit($com,Request $request,CommentairesRepository $commentairesRepository, EntityManagerInterface $em,Articles $article): Response
    {
       
        // $commentairess = $commentairesRepository -> findAll();
        $commentaires = $commentairesRepository->find($com);
        
        if($request->isMethod('POST')){

            // On récupère les données soumises dans le formulaire et on les attribue à l'entité $commentaires
            // $commentaires->setIdArticle($request->request->get('article'));

            $commentaires->setContenu($request->request->get('contenu')); // Attribue le contunu depuis la requête        
            
            $em->persist($commentaires); // Prépare l'entité $commentaires à être sauvegardée dans la base de données
            $em->flush(); // Sauvegarde réellement les données dans la base de données
            
            return $this->redirectToRoute('article_show', [
                'id'=> $article->getId()
            ]);
        }

        return $this->render('commentaires/edit.html.twig', [
            'article' => $article,
            'commentaires' => $commentaires
        ]);
    }

    #[Route('/{article}/commentaires/delete/{id}',name: 'commentaire_delete', methods: ['POST'])]
    public function delete($article,Commentaires $commentaires , EntityManagerInterface $entityManager): Response 
    {       
            $entityManager->remove($commentaires);
            $entityManager->flush();   

            return $this->redirectToRoute('article_show', [
                'id'=> $article
            ], Response::HTTP_SEE_OTHER);

    }


    // #[Route(name: 'api_commentaire_delete', methods: ['DELETE'])]
    // public function delete_api(Commentaires $commentaires , EntityManagerInterface $entityManager): Response 
    // {       
    //         $entityManager->remove($commentaires);
    //         $entityManager->flush();   

    //         return new JsonResponse(['status' => 'User deleted'], JsonResponse::HTTP_OK);
    // }
}

