<?php
// Déclaration du namespace. Cela permet de définir à quel "espace" ou "dossier" cette classe appartient
// Ici, la classe utilisateursController appartient à l'espace "App\Controller"
namespace App\Controller;

use App\Entity\Utilisateurs; // On importe la classe utilisateurs. Cela permet de manipuler les entités utilisateurs
use App\Repository\UtilisateursRepository; // On importe le repository utilisateurs pour accéder aux données de la table 'utilisateurs' dans la base de données
use Doctrine\ORM\EntityManagerInterface; // On importe EntityManagerInterface qui est utilisé pour interagir avec la base de données
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // Importation de la classe de base de Symfony qui fournit des méthodes utiles pour les contrôleurs
use Symfony\Component\HttpFoundation\Request; // Importation de la classe Request qui gère les données envoyées dans la requête HTTP
use Symfony\Component\HttpFoundation\Response; // Importation de la classe Response qui est utilisée pour envoyer une réponse HTTP
use Symfony\Component\Routing\Annotation\Route; // On importe l'annotation Route, qui permet de définir des routes pour ce contrôleur
 
#[Route('/utilisateurs')] // Cette annotation définit la route principale pour toutes les actions du contrôleur. Toutes les routes dans ce contrôleur commenceront par '/utilisateurss'
class UtilisateursController extends AbstractController // Déclaration de la classe utilisateursController qui étend la classe AbstractController (la classe de base des contrôleurs Symfony)
{
    #[Route('/', name: 'utilisateurs_index', methods: ['GET'])] // Cette annotation définit la route pour afficher la liste des utilisateurs. 'GET' signifie que cette route répondra aux requêtes HTTP GET (les requêtes pour obtenir des données)
    public function index(UtilisateursRepository $utilisateurRepository): Response // La méthode index() récupère tous les utilisateurs de la base de données via utilisateursRepository et les affiche
    {
        $utilisateurs = $utilisateurRepository->findAll(); // Appelle la méthode findAll() du utilisateursRepository pour récupérer tous les utilisateurs dans la base de données

        return $this->render('utilisateurs/index.html.twig', ['utilisateurs' => $utilisateurs]); // Rendu de la vue 'utilisateurs/index.html.twig', avec la liste des utilisateurs passée à la vue
    }


    #[Route('/new', name: 'utilisateurs_new', methods: ['GET', 'POST'])] // La route '/new' pour afficher le formulaire de création et traiter l'envoi du formulaire
    public function new(Request $request, EntityManagerInterface $em): Response // La méthode new() gère l'affichage et la création de nouveaux utilisateurs
    {
        if ($request->isMethod('POST')) { // Si la méthode de la requête est POST (c'est-à-dire que le formulaire a été soumis)
            $Utilisateur = new Utilisateurs(); // On crée une nouvelle instance de l'entité utilisateurs

            // On récupère les données soumises dans le formulaire et on les attribue à l'entité $utilisateurs
            $Utilisateur->setPseudo($request->request->get('Pseudo')); // Attribue le nom de l'utilisateur depuis la requête
            $Utilisateur->setEmail($request->request->get('Email')); // Attribue l'email depuis la requête

            // Hachage du mot de passe avant de le sauvegarder dans la base de données
            $hashedPassword = password_hash($request->request->get('Mot_de_Passe'), PASSWORD_BCRYPT); // Utilise bcrypt pour sécuriser le mot de passe
            $Utilisateur->setPassword($hashedPassword); // On attribue le mot de passe haché à l'utilisateur

            $role = $request->request->get('role', 'ROLE_utilisateur'); // On récupère le rôle du formulaire. Par défaut, il sera 'ROLE_utilisateurs'
            $Utilisateur->setRoles([$role]); // Attribue le rôle à l'utilisateur

            $em->persist($Utilisateur); // Prépare l'entité $utilisateurs à être sauvegardée dans la base de données
            $em->flush(); // Sauvegarde réellement les données dans la base de données

            return $this->redirectToRoute('utilisateurs_index'); // Redirige l'utilisateur vers la page de la liste des utilisateurs après l'ajout
        }

        return $this->render('utilisateurs/new.html.twig'); // Si la méthode est GET (formulaire de création), on affiche le formulaire
    }

    //a modifier 
    #[Route('/{id}/edit', name: 'utilisateurs_edit', methods: ['GET', 'POST'])] // La route '/{id}/edit' permet de modifier un utilisateur existant
    public function edit(Utilisateurs $Utilisateur, Request $request, EntityManagerInterface $em): Response // La méthode edit() permet de modifier les informations d'un utilisateur existant
    {
        if ($request->isMethod('POST')) { // Si la requête est de type POST (formulaire soumis)
            // Récupère et met à jour les informations de l'utilisateur
            
            $Utilisateur->setPhotoProfil($request->request->get('Photo_Profil')); // Modifie l'email de l'utilisateur
            
            $Utilisateur->setPseudo($request->request->get('Pseudo')); // Modifie le nom de l'utilisateur

            $Utilisateur->setEmail($request->request->get('Email')); // Modifie l'email de l'utilisateur

            $Utilisateur->setNom($request->request->get('Nom')); // Modifie l'email de l'utilisateur

            $Utilisateur->setPrenom($request->request->get('Prenom')); // Modifie l'email de l'utilisateur

            
            // Récupération de l'âge sous forme de chaîne
            $age = $request->request->get('Age'); 

            // Conversion en objet DateTime
            $dateTimeAge = $age ? \DateTime::createFromFormat('d-m-Y', $age) : null;

            $Utilisateur->setAge($dateTimeAge);

            $Utilisateur->setGenre($request->request->get('Genre'));

            $Utilisateur->setDescription($request->request->get('Description')); // Modifie l'email de l'utilisateur
            
            $em->flush(); // Sauvegarde les modifications apportées à l'utilisateur dans la base de données

            return $this->redirectToRoute('utilisateurs_index'); // Redirige vers la page de la liste des utilisateurs après modification
        }

        return $this->render('utilisateurs/edit.html.twig', ['Utilisateur' => $Utilisateur]); // Affiche le formulaire avec les données de l'utilisateur à modifier
    }

    #[Route('/{id}/delete', name: 'utilisateurs_delete', methods: ['POST'])] // La route '/{id}/delete' permet de supprimer un utilisateur
    public function delete(Utilisateurs $Utilisateur, EntityManagerInterface $em): Response // La méthode delete() permet de supprimer un utilisateur existant
    {
        $em->remove($Utilisateur); // Supprime l'utilisateur de la base de données
        $em->flush(); // Sauvegarde la suppression dans la base de données

        return $this->redirectToRoute('utilisateurs_index'); // Redirige vers la liste des utilisateurs après suppression
    }

    #[Route('/{id}/reinitialisation', name: 'utilisateurs_reinitialisation', methods: ['GET', 'POST'])] 
    public function reinitialisation(Utilisateurs $Utilisateur, Request $request, EntityManagerInterface $em): Response 
    {
        if ($request->isMethod('POST')) { // Si la requête est de type POST (formulaire soumis)
            
            // Hachage du mot de passe avant de le sauvegarder dans la base de données
            $hashedPassword = password_hash($request->request->get('Mot_de_Passe'), PASSWORD_BCRYPT); // Utilise bcrypt pour sécuriser le mot de passe
            $Utilisateur->setPassword($hashedPassword); // On attribue le mot de passe haché à l'utilisateur
            
            $em->flush(); // Sauvegarde les modifications apportées à l'utilisateur dans la base de données

            return $this->redirectToRoute('utilisateurs_index'); // Redirige vers la page de la liste des utilisateurs après modification
        }
        return $this->render('utilisateurs/reinitialisation.html.twig');
    }
}

