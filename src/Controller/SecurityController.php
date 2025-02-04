<?php

// Déclaration du namespace. Cela permet de définir à quel "espace" ou "dossier" cette classe appartient
// Ici, la classe SecurityController appartient à l'espace "App\Controller"
namespace App\Controller;

use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route; // On importe l'annotation Route pour définir les routes de ce contrôleur
use Symfony\Component\HttpFoundation\Response; // On importe la classe Response, qui est utilisée pour envoyer des réponses HTTP
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // On importe la classe de base AbstractController de Symfony, qui permet d'utiliser les méthodes de base pour un contrôleur
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils; // On importe AuthenticationUtils pour gérer l'authentification et récupérer des informations sur l'utilisateur connecté (erreurs de connexion, dernier nom d'utilisateur)

class SecurityController extends AbstractController // Déclaration de la classe SecurityController qui étend AbstractController, ce qui permet d'hériter de nombreuses méthodes utiles pour le contrôleur
{
    #[Route('/login', name: 'app_login')] // Annotation définissant la route '/login' pour afficher le formulaire de connexion. Elle associe cette route à la méthode 'login' de ce contrôleur
    public function login(AuthenticationUtils $authenticationUtils): Response // La méthode 'login' gère la connexion des utilisateurs en affichant le formulaire et en récupérant les erreurs de connexion
    {
        $error = $authenticationUtils->getLastAuthenticationError(); // Récupère l'erreur de dernière authentification, s'il y en a une (par exemple, mot de passe incorrect)
        $lastUsername = $authenticationUtils->getLastUsername(); // Récupère le dernier nom d'utilisateur utilisé lors de la tentative de connexion (en cas d'erreur, le nom d'utilisateur est déjà pré-rempli dans le formulaire)

        // Rend la vue 'security/login.html.twig' et passe les variables nécessaires (dernier nom d'utilisateur et erreur de connexion) à la vue pour l'affichage
        return $this->render('security/LoginForm.html.twig', [
            'last_username' => $lastUsername, // Transfert du dernier nom d'utilisateur à la vue pour l'affichage
            'error' => $error, // Transfert de l'erreur de connexion à la vue pour l'affichage
        ]);
    }

    #[Route('/logout', name: 'app_logout')] // Annotation définissant la route '/logout' pour gérer la déconnexion des utilisateurs
    public function logout(): void {} // Méthode vide pour gérer la déconnexion. Symfony gère automatiquement la déconnexion, donc cette méthode ne nécessite pas de code

    #[Route('/inscription', name: 'inscription', methods: ['GET', 'POST'])] 
    public function inscription(Request $request, EntityManagerInterface $em): Response 
    {
        if ($request->isMethod('POST')) { // Si la méthode de la requête est POST (c'est-à-dire que le formulaire a été soumis)
            $Utilisateur = new Utilisateurs(); // On crée une nouvelle instance de l'entité utilisateurs

            $Utilisateur->setPhotoProfil("styles/Image/Avatar_Guest.jpg"); // On définit par défault la photo de profil de l'utilisateur
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

            return $this->redirectToRoute('utilisateurs_profil'); // Redirige l'utilisateur vers la page de la liste des utilisateurs après l'ajout
        }

        return $this->render('security/Inscription.html.twig'); // Si la méthode est GET (formulaire de création), on affiche le formulaire
    }
}

