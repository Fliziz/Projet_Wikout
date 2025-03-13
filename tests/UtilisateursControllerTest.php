<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UtilisateursRepository;
use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;

class UtilisateursControllerTest extends WebTestCase
{
    private $client;
    private $entityManager;
    private $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->userRepository = static::getContainer()->get(UtilisateursRepository::class);
    }

    public function testIndexPageIsAccessible(): void
    {
        $this->client->request('GET', '/utilisateurs/admin');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
    }


    public function testAdminCanDeleteUser(): void
    {
        $adminUser = new Utilisateurs();
        $adminUser->setPseudo('AdminUser')
                  ->setEmail('admin@example.com')
                  ->setPassword(password_hash('Admin@Password123', PASSWORD_BCRYPT))
                  ->setRoles(['ROLE_ADMIN']);

        $userToDelete = new Utilisateurs();
        $userToDelete->setPseudo('UserToDelete')
                     ->setEmail('deleteuser@example.com')
                     ->setPassword(password_hash('Test@Password123', PASSWORD_BCRYPT))
                     ->setRoles(['ROLE_UTILISATEUR']);

        $this->entityManager->persist($adminUser);
        $this->entityManager->persist($userToDelete);
        $this->entityManager->flush();

        $this->client->loginUser($adminUser); //loginUser simule la connexion d'un utilisateur sans avoir à passer par le formulaire de connexion

        $this->client->request('POST', '/utilisateurs/admin/' . $userToDelete->getId() . '/delete');
        $this->assertResponseRedirects('/utilisateurs/admin');

        $deletedUser = $this->userRepository->find($userToDelete->getId());
        $this->assertNull($deletedUser);
    }
}
