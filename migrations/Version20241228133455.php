<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241228133455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, fiche_id INT NOT NULL, utilisateur_id INT NOT NULL, contenu LONGTEXT NOT NULL, INDEX IDX_D9BEC0C4DF522508 (fiche_id), UNIQUE INDEX UNIQ_D9BEC0C4FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(30) NOT NULL, nom VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE difficulte (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_contenu (id INT AUTO_INCREMENT NOT NULL, fiche_id INT NOT NULL, fiche_muscle_id INT NOT NULL, image1 VARCHAR(255) DEFAULT NULL, contenu1 LONGTEXT DEFAULT NULL, contenu2 LONGTEXT DEFAULT NULL, image2 VARCHAR(255) DEFAULT NULL, contenu3 LONGTEXT DEFAULT NULL, image3 VARCHAR(255) DEFAULT NULL, etude LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_91C35BE5DF522508 (fiche_id), UNIQUE INDEX UNIQ_91C35BE5B221665C (fiche_muscle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_muscles (id INT AUTO_INCREMENT NOT NULL, fiche_contenu_id INT NOT NULL, muscles_id INT NOT NULL, UNIQUE INDEX UNIQ_33497A21924AC07F (fiche_contenu_id), UNIQUE INDEX UNIQ_33497A212B48856B (muscles_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiches (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, categorie_id INT NOT NULL, type_id INT NOT NULL, difficulte_id INT NOT NULL, nom VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_459C25C9FB88E14F (utilisateur_id), UNIQUE INDEX UNIQ_459C25C9BCF5E72D (categorie_id), UNIQUE INDEX UNIQ_459C25C9C54C8C93 (type_id), UNIQUE INDEX UNIQ_459C25C9E6357589 (difficulte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE muscles (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs (id INT AUTO_INCREMENT NOT NULL, photo_profil VARCHAR(255) DEFAULT NULL, pseudo VARCHAR(20) NOT NULL, email VARCHAR(50) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, nom VARCHAR(50) DEFAULT NULL, prenom VARCHAR(50) DEFAULT NULL, age DATE DEFAULT NULL, genre VARCHAR(10) DEFAULT NULL, description LONGTEXT DEFAULT NULL, role JSON NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4DF522508 FOREIGN KEY (fiche_id) REFERENCES fiches (id)');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE fiche_contenu ADD CONSTRAINT FK_91C35BE5DF522508 FOREIGN KEY (fiche_id) REFERENCES fiches (id)');
        $this->addSql('ALTER TABLE fiche_contenu ADD CONSTRAINT FK_91C35BE5B221665C FOREIGN KEY (fiche_muscle_id) REFERENCES fiche_muscles (id)');
        $this->addSql('ALTER TABLE fiche_muscles ADD CONSTRAINT FK_33497A21924AC07F FOREIGN KEY (fiche_contenu_id) REFERENCES fiche_contenu (id)');
        $this->addSql('ALTER TABLE fiche_muscles ADD CONSTRAINT FK_33497A212B48856B FOREIGN KEY (muscles_id) REFERENCES muscles (id)');
        $this->addSql('ALTER TABLE fiches ADD CONSTRAINT FK_459C25C9FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE fiches ADD CONSTRAINT FK_459C25C9BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE fiches ADD CONSTRAINT FK_459C25C9C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE fiches ADD CONSTRAINT FK_459C25C9E6357589 FOREIGN KEY (difficulte_id) REFERENCES difficulte (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4DF522508');
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4FB88E14F');
        $this->addSql('ALTER TABLE fiche_contenu DROP FOREIGN KEY FK_91C35BE5DF522508');
        $this->addSql('ALTER TABLE fiche_contenu DROP FOREIGN KEY FK_91C35BE5B221665C');
        $this->addSql('ALTER TABLE fiche_muscles DROP FOREIGN KEY FK_33497A21924AC07F');
        $this->addSql('ALTER TABLE fiche_muscles DROP FOREIGN KEY FK_33497A212B48856B');
        $this->addSql('ALTER TABLE fiches DROP FOREIGN KEY FK_459C25C9FB88E14F');
        $this->addSql('ALTER TABLE fiches DROP FOREIGN KEY FK_459C25C9BCF5E72D');
        $this->addSql('ALTER TABLE fiches DROP FOREIGN KEY FK_459C25C9C54C8C93');
        $this->addSql('ALTER TABLE fiches DROP FOREIGN KEY FK_459C25C9E6357589');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE commentaires');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE difficulte');
        $this->addSql('DROP TABLE fiche_contenu');
        $this->addSql('DROP TABLE fiche_muscles');
        $this->addSql('DROP TABLE fiches');
        $this->addSql('DROP TABLE muscles');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE utilisateurs');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
