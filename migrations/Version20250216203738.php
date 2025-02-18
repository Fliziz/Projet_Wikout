<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216203738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateurs (id)');
        $this->addSql('DROP INDEX UNIQ_33497A21924AC07F ON fiche_muscles');
        $this->addSql('DROP INDEX UNIQ_33497A212B48856B ON fiche_muscles');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_33497A21924AC07F ON fiche_muscles (fiche_contenu_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_33497A212B48856B ON fiche_muscles (muscles_id)');
        $this->addSql('ALTER TABLE fiches CHANGE utilisateur_id utilisateur_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE fiches CHANGE utilisateur_id utilisateur_id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_33497A21924AC07F ON fiche_muscles');
        $this->addSql('DROP INDEX UNIQ_33497A212B48856B ON fiche_muscles');
        $this->addSql('CREATE INDEX UNIQ_33497A21924AC07F ON fiche_muscles (fiche_contenu_id)');
        $this->addSql('CREATE INDEX UNIQ_33497A212B48856B ON fiche_muscles (muscles_id)');
    }
}
