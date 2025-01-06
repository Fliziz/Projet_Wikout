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
use App\Repository\MusclesRepository;
use App\Entity\Fiches;
use App\Entity\Categorie;
use App\Entity\FicheContenu;
use App\Entity\FicheMuscles;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/fiches')]
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

    #[Route('/newPreview', name: 'fiches_newPreview', methods: ['GET', 'POST'])] // La route '/new' pour afficher le formulaire de création et traiter l'envoi du formulaire

    public function newPreview(Request $request, EntityManagerInterface $em , UtilisateursRepository $utilisateursRepository , CategoriesRepository $categoriesRepository , TypeRepository $typeRepository , DifficulteRepository $difficulteRepository , SessionInterface $session): Response // La méthode new() gère l'affichage et la création de nouveaux fiches/ on met categorie en tant qu'attribut car on veut recupérer toute les catégories existante
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
        
            // Stocker l'objet fiche dans la session de l'utilisateur (session permet de stocke des informations de l'utilisateur de requete en requete et sur chaque page)
            $session->set('fiche_preview', $fiche);

            return $this->redirectToRoute('fiches_newContenu'); // Redirige l'administrateur vers la page de la liste des fiches après l'ajout
        }

        return $this->render('fiches/newPreview.html.twig', ['categories' => $categories , 'types' => $types , 'difficultes' => $difficultes]); // Si la méthode est GET (formulaire de création), on affiche le formulaire
    }

    #[Route('/newContenu', name: 'fiches_newContenu', methods: ['GET', 'POST'])] // La route '/new' pour afficher le formulaire de création et traiter l'envoi du formulaire

    public function newContenu(Request $request, EntityManagerInterface $em, MusclesRepository $musclesRepository,SessionInterface $session ): Response 
    // La méthode newContenu() gère l'affichage et la création du contenue des nouvelle fiches
    {
        
        $fiche = $session->get('fiche_preview');// Récupérer la fiche stockée dans la session de l'utilisateur actuel
        $muscles = $musclesRepository->findall();//Utilise la methode du répository MusclesRepository pour récupper tout le contenu de la table 
    
        if (!$fiche) {
             // Si aucune fiche n'est trouvée en session, on est alors rediriger vers la page de création de la preview(plus de sécurité)
            return $this->redirectToRoute('fiches_newPreview');
        }
        if ($request->isMethod('POST')) { // Si la méthode de la requête est POST (c'est-à-dire que le formulaire a été soumis)
            
            $ficheContenu = new FicheContenu(); // On crée une nouvelle instance de l'entité FicheContenu
           
            $ficheContenu->setFiche($fiche);//on definit l'attibut Fiche avec l'objet fiche qui contient notre FichePreview
            $ficheContenu->setImage1($request->request->get('image1')); // Attribue le contunu depuis la requête
            $ficheContenu->setContenu1($request->request->get('contenu1')); // Attribue le titre depuis la requête
            $ficheContenu->setContenu2($request->request->get('contenu2')); // Attribue le titre depuis la requête
            $ficheContenu->setImage2($request->request->get('image2')); // Attribue le contunu depuis la requête
            $ficheContenu->setContenu3($request->request->get('contenu3')); // Attribue le titre depuis la requête
            $ficheContenu->setImage3($request->request->get('image3')); // Attribue le contunu depuis la requête
            $ficheContenu->setEtude($request->request->get('etude')); // Attribue le contunu depuis la requête

            
            $em->persist($ficheContenu); // Prépare l'entité $fiche à être sauvegardée dans la base de données
            $em->persist($fiche); // Prépare l'entité $fiche à être sauvegardée dans la base de données
        
            $musclesSelectionnes = $request->request->all('muscles'); // Cela défini dans la variable le tableau d'id des muscles selectionné avec les chekbox
            //Ainsi on choisit ici une methode all car il nous permet de retourner un tableau alors que get return seulement un string/bool/int/float/null
            if ($musclesSelectionnes) {
                foreach ($musclesSelectionnes as $muscleId) {
                    // Récupérer le muscle par son ID
                    $muscle = $musclesRepository->find($muscleId);
                    if ($muscle) {
                        $MusclesListe = new FicheMuscles();
                        $MusclesListe->setFicheContenu($ficheContenu); // Attribue le contunu depuis la requête
                        $MusclesListe->setMuscles($muscle);
                        $em->persist($MusclesListe);
                    }
                }
            }

            $em->persist($MusclesListe);
            
            $em->flush(); // Sauvegarde réellement les données dans la base de données

            // Supprimer la fiche de la session utilisateur une fois qu'elle a été enregistrée
            $session->remove('fiche_preview'); 

            return $this->redirectToRoute('fiches_index' ); // Redirige l'administrateur vers la page de la liste des fiches après l'ajout
        }

        return $this->render('fiches/newContenu.html.twig',['muscles' => $muscles]); // Si la méthode est GET (formulaire de création), on affiche le formulaire
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
    public function show(Fiches $fiche, CommentaireRepository $commentaireRepository): Response
    {
        
         // Récupérer l'ID de la catégorie depuis le formulaire
         $ficheId = $fiche->getid();

         // Rechercher l'entité Categorie correspondante
        //  $commentaires = $commentaireRepository->findBy(['id_fiche' =>$ficheId]);

        return $this->render('fiches/show.html.twig', [
            'fiche' => $fiche,
            // 'commentaires' => $commentaires
        ]);
    }
}

// Voici mon controler pour l'entité fiches, comment puis-je faire pour que lorsque je submit mon form newPreview j'envoie l'id de la fiche vers newContenu pour le récupérer est le mettre dans l'attribut id_fiches de newContenu