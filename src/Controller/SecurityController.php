<?php

// Déclaration du namespace. Cela permet de définir à quel "espace" ou "dossier" cette classe appartient
// Ici, la classe SecurityController appartient à l'espace "App\Controller"
namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Service\MongoDBService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function inscription(MongoDBService $mongoDBService,CsrfTokenManagerInterface $csrfTokenManager,Request $request,EntityManagerInterface $em,ValidatorInterface $validator,UserPasswordHasherInterface $passwordHasher ): Response 
    {
        $csrfToken = $csrfTokenManager->getToken('inscription')->getValue();

        $mongoDBService->inserVisit('inscription');

        if ($request->isMethod('POST')) { // Vérifie si le formulaire a été soumis

            // Vérification du token CSRF
            $token = $request->request->get('_csrf_token');
            if (!$csrfTokenManager->isTokenValid(new CsrfToken('inscription', $token))) {
                throw new AccessDeniedHttpException('Le token CSRF est invalide.');
            }

            // Création de l'utilisateur
            $Utilisateur = new Utilisateurs();

            // Valeurs par défaut et récupération des données
            $Utilisateur->setPhotoProfil("styles/Image/Avatar_Guest.png");
            $Utilisateur->setPseudo($request->request->get('Pseudo'));
            $Utilisateur->setEmail($request->request->get('Email'));
            $role = $request->request->get('role', 'ROLE_Utilisateur');
            $Utilisateur->setRoles([$role]);

            // Récupération du mot de passe brut
            $plainPassword = $request->request->get('Mot_de_Passe');
            $Utilisateur->setPassword($plainPassword); // On met d'abord le mot de passe brut pour la validation

            // Validation des contraintes de l'entité
            $errors = $validator->validate($Utilisateur);
            
            if (count($errors) > 0) {
                return $this->render('security/Inscription.html.twig', [
                    'errors' => $errors,
                    'csrf_token' => $csrfToken,
                ]);
            }

            // Hashage du mot de passe après validation
            $hashedPassword = $passwordHasher->hashPassword($Utilisateur, $plainPassword);
            $Utilisateur->setPassword($hashedPassword); // Remplace le mot de passe par sa version hashée

            // Sauvegarde dans la base de données
            $em->persist($Utilisateur);
            $em->flush();

            return $this->redirectToRoute('app_login'); // Redirection après l'inscription
        }

        return $this->render('security/Inscription.html.twig', [
            'csrf_token' => $csrfToken, 
        ]);
    }
}

