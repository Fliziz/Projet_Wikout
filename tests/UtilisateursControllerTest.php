<?php

// namespace App\Tests\Controller;

// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
// use App\Repository\UtilisateursRepository;
// use App\Entity\Utilisateurs;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// class UtilisateursControllerTest extends WebTestCase
// {
//     private $client;
//     private $entityManager;
//     private $userRepository;
//     private $passwordHasher;

//     protected function setUp(): void 
//     {
//         $this->client = static::createClient();
//         $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
//         $this->userRepository = static::getContainer()->get(UtilisateursRepository::class);
//         $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
//     }

//     private function createUser(string $pseudo, string $email, string $mdp, string $role): Utilisateurs
//     {
//         $user = new Utilisateurs();
//         $user->setPseudo($pseudo)
//              ->setEmail($email)
//              ->setPassword($this->passwordHasher->hashPassword($user, $mdp)) // Utilisation du passwordHasher
//              ->setRoles([$role]);

//         $this->entityManager->persist($user);
//         $this->entityManager->flush();
        
//         return $user;
//     }

//     public function testIndexPageIsAccessible(): void
//     {   
        

//         // Récupération de l'utilisateur après insertion
//         $adminUser = $this->createUser('AdminUser1', 'admin1@example.com', 'Admin@Password123', 'ROLE_ADMIN');
//         $this->client->loginUser($adminUser);

//         $crawler = $this->client->request('GET', '/utilisateurs/admin');
//         $this->assertResponseIsSuccessful();
    // }

    // public function testAdminCanDeleteUser(): void
    // {
        
        

    //     // Récupération des utilisateurs après insertion
    //     $adminUser = $this->createUser('AdminUser2', 'admin2@example.com', 'Admin@Password123', 'ROLE_ADMIN');
    //     $userToDelete = $this->createUser('UserToDelete', 'deleteuser@example.com', 'Test@Password123', 'ROLE_UTILISATEUR');

    //     $this->client->loginUser($adminUser);
    //     $this->client->request('POST', '/utilisateurs/admin/' . $userToDelete->getId() . '/delete');
    //     $this->assertResponseRedirects('/utilisateurs/admin');

    //     // Rafraîchir l'EntityManager pour éviter le cache
    //     $this->entityManager->clear();
        
    //     $deletedUser = $this->userRepository->find($userToDelete->getId());
    //     $this->assertNull($deletedUser);
    // }

    // protected function tearDown(): void
    // {
    //     parent::tearDown();
    //     $this->entityManager->close();
    //     $this->entityManager = null;
    //     $this->userRepository = null;
    //     $this->passwordHasher = null;
    //     $this->client = null;
    // }
// } 
