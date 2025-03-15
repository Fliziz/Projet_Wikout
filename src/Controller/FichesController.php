<?php

namespace App\Controller;

use App\Entity\Fiches;
use App\Entity\Categorie;
use App\Entity\FicheContenu;
use App\Entity\FicheMuscles;
use App\Service\MongoDBService;
use App\Repository\TypeRepository;
use App\Repository\FichesRepository;
use App\Repository\MusclesRepository;
use App\Repository\CategoriesRepository;
use App\Repository\DifficulteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentairesRepository;
use App\Repository\FicheContenuRepository;
use App\Repository\FicheMusclesRepository;
use App\Repository\UtilisateursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/fiches')]
class FichesController extends AbstractController
{

    #[Route('/', name: 'fiches_index', methods: ['GET', 'POST'])]
    public function index( MongoDBService $mongoDBService ,FichesRepository $fichesRepository, CategoriesRepository $categoriesRepository,DifficulteRepository $difficulteRepository,TypeRepository $typesRepository, Request $request): Response
    {   
        $categories = $categoriesRepository->findAll(); // Récupère toutes les catégories
        $difficultes = $difficulteRepository->findAll(); // Récupère toutes les difficultes
        $types = $typesRepository->findAll(); // Récupère toutes les type

        $mongoDBService->inserVisit('fiches');

        // Récupération des valeurs des filtres
        $Recherche = $request->request->get('recherche'); // Mot-clé pour la recherche
        $selectCategorie = $request->request->get('filtrecategorie');    // ID de la catégorie à filtrer
        $selectDifficulte = $request->request->get('filtredifficulte');    // ID de la Difficulte à filtrer
        $selectType = $request->request->get('filtremuscles');    // ID de la Type à filtrer

        // Appel à la méthode du repository pour combiner recherche et filtre
        $fiches = $fichesRepository->findByTitleOrCategoryOrMusclesOrDifficulte($Recherche ,$selectCategorie ,$selectDifficulte ,$selectType);

        return $this->render('fiches/IndexAdmin.html.twig', [
            'fiches' => $fiches,
            'categories' => $categories,
            'difficultes' => $difficultes,
            'types' => $types,
            'selectCategorie' => $selectCategorie, // Pour garder la catégorie sélectionnée dans le formulaire
            'selectDifficulte' => $selectDifficulte, // Pour garder la catégorie sélectionnée dans le formulaire
            'selectType' => $selectType, // Pour garder la catégorie sélectionnée dans le formulaire
            'Recherche' => $Recherche       // Pour garder le terme de recherche dans le formulaire
        ]);
    }

    #[Route('/admin/newPreview', name: 'fiches_newPreview', methods: ['GET', 'POST'])] // La route '/newPreview' pour afficher le formulaire de création et traiter l'envoi du formulaire

    public function newPreview(ValidatorInterface $validator, CsrfTokenManagerInterface $csrfTokenManager,Request $request, EntityManagerInterface $em , UtilisateursRepository $utilisateursRepository , CategoriesRepository $categoriesRepository , TypeRepository $typeRepository , DifficulteRepository $difficulteRepository ): Response 
    // La méthode newPreview() permet de crée les petites fiches ou les utilisateurs peuvent cliquer dessus pour accéder au contenu
    //Request (requete http) qui permet de recupere la requete d'un formulaire, EntityManager permet gérer les intéraction entre les entités
    //Pour finir on inporte les Repository pour permet d'utiliser les methodes de base des repository qui nous permete de faire des requetes SQL
    {

        $categories = $categoriesRepository->findAll();//Requete SQL qui cherche toute les categorie dans la table est les met dans $categories
        $types = $typeRepository->findAll();
        $difficultes = $difficulteRepository->findAll();

        $csrfToken = $csrfTokenManager->getToken('fiches_newPreview')->getValue();

        if ($request->isMethod('POST')) { // Si la méthode de la requête est POST (c'est-à-dire que le formulaire a été soumis)
            
            // Récupérer et vérifier le token CSRF
            $token = $request->request->get('_csrf_token');

            if (!$csrfTokenManager->isTokenValid(new CsrfToken('fiches_newPreview', $token))) {

                throw new AccessDeniedHttpException('Le token CSRF est invalide.');
            }

            $fiche = new Fiches(); // On crée une nouvelle instance de l'entité Fiches

            // On récupère les données soumises dans le formulaire et on les attribue à l'objet de l'entité $fiche


            $fiche->setNom($request->request->get('Nom')); // Attribue le Nom depuis la requête
            $fiche->setPhoto($request->request->get('Photo')); 
            
            $fiche->setDescription($request->request->get('Description'));  

            $fiche->setUtilisateur($utilisateursRepository->find($request->request->get('Utilisateur')));//On recupere l'utilisateur grace a une requete sql en fontion de l'id utilisateur present dans la requete

            // Récupérer l'ID de la catégorie depuis le formulaire
            $categorieId = $request->request->get('Categorie_id');

            // Rechercher l'entité Categorie dans la table grace a une requete sql
            $categorie = $categoriesRepository->find($categorieId);
           
            // Attribue l'entité categorie a la variable fiche
            $fiche->setCategorie($categorie); 

           //Pour moin de ligne on peut combiner les ligne 71 et 77 on va directement chercher le type grace a la requete
            $types = $typeRepository->find($request->request->get('Type_id'));
            
            
            $fiche->setType($types); 

           
            $difficultes = $difficulteRepository->find($request->request->get('Difficulte_id'));
           
        
            $fiche->setDifficulte($difficultes); 

            $errors = $validator->validate($fiche);
            
            if (count($errors) > 0) {
                return $this->render('error.html.twig', [
                    'errors' => $errors,
                ]);
            }
        
            $em->persist($fiche); // Prépare l'entité $fiche à être sauvegardée dans la base de données
            $em->flush();//Envoie réelement la fiche dans l'entité

            // Redirige l'administrateur vers la page de création du contenu de la fiche une fois fini
            return $this->redirectToRoute('fiches_newContenu',[
                'id' => $fiche->getId(),
            ]); 
        }
         
        // Si la méthode est GET (formulaire de création), on affiche le formulaire et on envoie les categorie ect pour les afficher sur la vue
        return $this->render('fiches/newPreview.html.twig', [
            'categories' => $categories , 
            'types' => $types , 
            'difficultes' => $difficultes,
            'csrf_token' => $csrfToken // Passe le token CSRF à la vue
    
    ]);
    }

    #[Route('/admin/{id}/newContenu', name: 'fiches_newContenu', methods: ['GET', 'POST'])] // La route '/new' pour afficher le formulaire de création et traiter l'envoi du formulaire

    public function newContenu(CsrfTokenManagerInterface $csrfTokenManager,Fiches $fiche ,Request $request, EntityManagerInterface $em, MusclesRepository $musclesRepository): Response 
    // La méthode newContenu() gère l'affichage et la création du contenue des nouvelle fiches
    {
        

        $muscles = $musclesRepository->findall();//Utilise la methode du répository MusclesRepository pour récupper tout le contenu de la table 
        $csrfToken = $csrfTokenManager->getToken('fiches_newContenu')->getValue();

        if ($request->isMethod('POST')) { // Si la méthode de la requête est POST (c'est-à-dire que le formulaire a été soumis)
            
             // Récupérer et vérifier le token CSRF
            $token = $request->request->get('_csrf_token');
            
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('fiches_newContenu', $token))) {

                throw new AccessDeniedHttpException('Le token CSRF est invalide.');
            }

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

            $em->flush(); // Sauvegarde réellement les données dans la base de données


            return $this->redirectToRoute('fiches_index' ); // Redirige l'administrateur vers la page de la liste des fiches après l'ajout
        }

        return $this->render('fiches/newContenu.html.twig',[
            'muscles' => $muscles,
            'csrf_token' => $csrfToken // Passe le token CSRF à la vue
        ]); // Si la méthode est GET (formulaire de création), on affiche le formulaire
    }
    
    #[Route('/admin/edition/{id}', name: 'fiches_edit', methods: ['GET', 'POST'])] // La route '/{id}/edit' permet de modifier une fiches existante
    public function edit(CsrfTokenManagerInterface $csrfTokenManager,Fiches $fiche, Request $request, EntityManagerInterface $em , CategoriesRepository $categoriesRepository , TypeRepository $typeRepository , DifficulteRepository $difficulteRepository ): Response 
    // La méthode edit() permet de modifier les informations d'une fiches existante
    //Ici l'objet $fiche represente la fiche avec l'id qui est dans l'url(symfony lie fiche automatiquement a l'id du lien)
    {

        $categories = $categoriesRepository->findAll();//Requete SQL qui cherche toute les categorie dans la table est les met dans $categories
        $types = $typeRepository->findAll();
        $difficultes = $difficulteRepository->findAll();

        $csrfToken = $csrfTokenManager->getToken('fiches_edit')->getValue();

        if ($request->isMethod('POST')) { // Si la méthode de la requête est POST (c'est-à-dire que le formulaire a été soumis)

            // Récupérer et vérifier le token CSRF
            $token = $request->request->get('_csrf_token');
            
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('fiches_edit', $token))) {

                throw new AccessDeniedHttpException('Le token CSRF est invalide.');
            }

            // On récupère les données soumises dans le formulaire et on les attribue à l'entité $fiche
            $fiche->setNom($request->request->get('Nom')); // Attibut maintenant nom depuis la requête
            $fiche->setPhoto($request->request->get('Photo')); 
            $fiche->setDescription($request->request->get('Description')); 
            
            $fiche->setEditer($request->request->get('Editeur'));

            // Rechercher l'entité Categorie correspondante
            $categorie = $categoriesRepository->find($request->request->get('Categorie_id'));
           
            //Attribue l'entité categorie a la variable fiche
            $fiche->setCategorie($categorie); 
            //
            $types = $typeRepository->find($request->request->get('Type_id'));
            
            $fiche->setType($types); 
            //
            $difficultes = $difficulteRepository->find($request->request->get('Difficulte_id'));
           
            $fiche->setDifficulte($difficultes); 
            
            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                return $this->render('error.html.twig', [
                    'errors' => $errors,
                ]);
            }

            $em->persist($fiche); // Prépare l'entité $fiche à être sauvegardée dans la base de données
            $em->flush(); // Sauvegarde réellement les données dans la base de données
            
            // Redirige l'administrateur vers la page de la liste des fiches après l'ajout
            return $this->redirectToRoute('fiches_index'); 
        }

        // Affiche le formulaire avec les données de l'utilisateur récuperer grace au repo et a l'objet fiche
        return $this->render('fiches/edit.html.twig', 
        [
            'fiche' => $fiche , 
            'categories' => $categories, 
            'types' => $types , 
            'difficultes' => $difficultes,
            'csrf_token' => $csrfToken // Passe le token CSRF à la vue
        ]); 
    }

    #[Route('/{id}/admin/delete', name:  'fiches_delete', methods: ['POST'])] // La route '/{id}/delete' permet de supprimer un fiche
    public function delete(Fiches $fiche, FicheContenuRepository $ficheContenuRepository , FicheMusclesRepository $FMRepository ,EntityManagerInterface $em): Response // La méthode delete() permet de supprimer un fiche existant
    {   
        $ficheContenu = $ficheContenuRepository->findOneBy(['Fiche' => $fiche ]);
        $ficheMuscles = $FMRepository->findBy(['Fiche_Contenu' => $fiche ]);

        

        for ($muscle = 0 ;$muscle < count($ficheMuscles) ; $muscle++){
            $em->remove($ficheMuscles[$muscle]); // Supprime les muscles de la base de données
        }

        if($fiche == $ficheContenu->getFiche()){
            $em->remove($ficheContenu); // Supprime une fiche de la base de données
            $em->remove($fiche); // Supprime une fiche de la base de données	
            $em->flush(); // Sauvegarde la suppression dans la base de données
        }

        return $this->redirectToRoute('fiches_index'); // Redirige vers la liste des utilisateurs après suppression
    }

    
    #[Route('/{id}', name: 'fiche_show', methods: ['GET'])]
    public function show(Fiches $fiche, FicheContenuRepository $ficheContenuRepository ,CommentairesRepository $commentairesRepository, FicheMusclesRepository $FMRepository): Response
    {
        //$fiche contient la fiche qui est d'id dans l'url car symfony fait la liason automatiquement.

        $ficheContenu = $ficheContenuRepository->findOneBy(['Fiche' => $fiche]);     
        // // Rechercher les comentaire avec l'id de la fiche comme id_fiche
        $commentaires = $commentairesRepository->findBy(['FicheContenu' => $ficheContenu ]);
        
        $muscles = $FMRepository->findBy(['Fiche_Contenu' => $ficheContenu ]);

        return $this->render('fiches/show.html.twig', [
            'FicheContenu' => $ficheContenu,
            'commentaires' => $commentaires,
            'Muscles' => $muscles
        ]);
    }
}

// Voici mon controler pour l'entité fiches, comment puis-je faire pour que lorsque je submit mon form newPreview j'envoie l'id de la fiche vers newContenu pour le récupérer est le mettre dans l'attribut id_fiches de newContenu