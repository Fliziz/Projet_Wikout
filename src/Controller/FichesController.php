<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\FichesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\TypeRepository;
use App\Repository\DifficulteRepository;
use App\Repository\UtilisateursRepository;
use App\Entity\Fiches;
use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/Fiches')]
class FichesController extends AbstractController
{

    #[Route('/', name: 'fiches_index', methods: ['GET', 'POST'])]
    public function index(FichesRepository $fichesRepository, CategoriesRepository $categoriesRepository, Request $request): Response
    {   
        $categories = $categoriesRepository->findAll(); // Récupère toutes les catégories

        // Récupération des valeurs des filtres
        $recherche = $request->request->get('recherche'); // Mot-clé pour la recherche
        $categorie = $request->request->get('filtre');    // ID de la catégorie à filtrer

        // Appel à la méthode du repository pour combiner recherche et filtre
        $fiches = $fichesRepository->findByTitleAndCategory($recherche, $categorie);

        $selectcategorie = $categorie;

        return $this->render('fiches/IndexAdmin.html.twig', [
            'fiches' => $fiches,
            'categories' => $categories,
            'selectcategorie' => $selectcategorie, // Pour garder la catégorie sélectionnée dans le formulaire
            'recherche' => $recherche       // Pour garder le terme de recherche dans le formulaire
        ]);
    }

    #[Route('/new', name: 'fiches_new', methods: ['GET', 'POST'])] // La route '/new' pour afficher le formulaire de création et traiter l'envoi du formulaire

    public function new(Request $request, EntityManagerInterface $em , UtilisateursRepository $utilisateursRepository , CategoriesRepository $categoriesRepository , TypeRepository $typeRepository , DifficulteRepository $difficulteRepository): Response // La méthode new() gère l'affichage et la création de nouveaux fiches/ on met categorie en tant qu'attribut car on veut recupérer toute les catégories existante
    // ca revient au meme que faire un categories => categorieRepositorie -> findAll() (en ayant au prealable import categoriesRepositorie)
    {

        $categories = $categoriesRepository->findAll();
        $types = $typeRepository->findAll();
        $difficultes = $difficulteRepository->findAll();

        if ($request->isMethod('POST')) { // Si la méthode de la requête est POST (c'est-à-dire que le formulaire a été soumis)
            $fiche = new fiches(); // On crée une nouvelle instance de l'entité User

            // On récupère les données soumises dans le formulaire et on les attribue à l'entité $user
            $fiche->setNom($request->request->get('Nom')); // Attribue le titre depuis la requête
            $fiche->setPhoto($request->request->get('Photo')); // Attribue le contunu depuis la requête
            $fiche->setDescription($request->request->get('Description')); // Attribue le contunu depuis la requête
            
            $fiche->setUtilisateur($utilisateursRepository->find($request->request->get('Utilisateur')));
            // Récupérer l'ID de la catégorie depuis le formulaire
            $categorieId = $request->request->get('Categorie_id');

            // Rechercher l'entité Categorie correspondante
            $categorie = $categoriesRepository->find($categorieId);
           
            // Attribue l'entité categorie a la variable fiche
            $fiche->setCategorie($categorie); 


             // Rechercher l'entité Types correspondante
            $types = $typeRepository->find($request->request->get('Type_id'));
            
            // Attribue l'entité categorie a la variable fiche
            $fiche->setType($types); 

            // Rechercher l'entité Categorie correspondante
            $difficultes = $difficulteRepository->find($request->request->get('Difficulte_id'));
           
            // Attribue l'entité categorie a la variable fiche
            $fiche->setDifficulte($difficultes); 
        
            $em->persist($fiche); // Prépare l'entité $fiche à être sauvegardée dans la base de données
            $em->flush(); // Sauvegarde réellement les données dans la base de données

            return $this->redirectToRoute('fiches_index'); // Redirige l'administrateur vers la page de la liste des fiches après l'ajout
        }

        return $this->render('fiches/new.html.twig', ['categories' => $categories , 'types' => $types , 'difficultes' => $difficultes]); // Si la méthode est GET (formulaire de création), on affiche le formulaire
    }
    
    #[Route('/edit/{id}', name: 'fiches_edit', methods: ['GET', 'POST'])] // La route '/{id}/edit' permet de modifier un utilisateur existant
    public function edit(Fiches $fiche, Request $request, EntityManagerInterface $em , CategoriesRepository $categoriesRepository , TypeRepository $typeRepository , DifficulteRepository $difficulteRepository ): Response // La méthode edit() permet de modifier les informations d'un utilisateur existant
    {

        $categories = $categoriesRepository->findAll();
        $types = $typeRepository->findAll();
        $difficultes = $difficulteRepository->findAll();

        if ($request->isMethod('POST')) { // Si la méthode de la requête est POST (c'est-à-dire que le formulaire a été soumis)

            // On récupère les données soumises dans le formulaire et on les attribue à l'entité $user
            $fiche->setNom($request->request->get('Nom')); // Attribue le titre depuis la requête
            $fiche->setPhoto($request->request->get('Photo')); // Attribue le contunu depuis la requête
            $fiche->setDescription($request->request->get('Description')); // Attribue le contunu depuis la requête
            
            $fiche->setEditer($request->request->get('Editeur'));

            // Récupérer l'ID de la catégorie depuis le formulaire
            $categorieId = $request->request->get('Categorie_id');

            // Rechercher l'entité Categorie correspondante
            $categorie = $categoriesRepository->find($categorieId);
           
            // Attribue l'entité categorie a la variable fiche
            $fiche->setCategorie($categorie); 


             // Rechercher l'entité Types correspondante
            $types = $typeRepository->find($request->request->get('Type_id'));
            
            // Attribue l'entité categorie a la variable fiche
            $fiche->setType($types); 

            // Rechercher l'entité Categorie correspondante
            $difficultes = $difficulteRepository->find($request->request->get('Difficulte_id'));
           
            // Attribue l'entité categorie a la variable fiche
            $fiche->setDifficulte($difficultes); 
        
            $em->persist($fiche); // Prépare l'entité $fiche à être sauvegardée dans la base de données
            $em->flush(); // Sauvegarde réellement les données dans la base de données

            return $this->redirectToRoute('fiches_index'); // Redirige l'administrateur vers la page de la liste des fiches après l'ajout
        }

        return $this->render('fiches/edit.html.twig', ['fiche' => $fiche , 'categories' => $categories, 'types' => $types , 'difficultes' => $difficultes]); // Affiche le formulaire avec les données de l'utilisateur à modifier
    }

    #[Route('/{id}/delete', name:  'fiches_delete', methods: ['POST'])] // La route '/{id}/delete' permet de supprimer un fiche
    public function delete(fiches $fiche, EntityManagerInterface $em): Response // La méthode delete() permet de supprimer un fiche existant
    {   

        $em->remove($fiche); // Supprime l'utilisateur de la base de données
        $em->flush(); // Sauvegarde la suppression dans la base de données

        return $this->redirectToRoute('fiches_index'); // Redirige vers la liste des utilisateurs après suppression
    }

    
    #[Route('/{id}', name: 'fiche_show', methods: ['GET'])]
    public function show(fiches $fiche, CommentaireRepository $commentaireRepository): Response
    {
        
         // Récupérer l'ID de la catégorie depuis le formulaire
         $ficheId = $fiche->getid();

         // Rechercher l'entité Categorie correspondante
         $commentaires = $commentaireRepository->findBy(['id_fiche' =>$ficheId]);

        return $this->render('fiches/show.html.twig', [
            'fiche' => $fiche,
            'commentaires' => $commentaires
        ]);
    }
}
