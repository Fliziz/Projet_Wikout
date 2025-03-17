<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250317173235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add CASCADE delete to fiche_muscles table';
    }

    public function up(Schema $schema): void
    {
        // Suppression de l'ancienne contrainte
        $this->addSql('ALTER TABLE fiche_muscles DROP FOREIGN KEY FK_33497A21924AC07F');
        
        // Ajout de la nouvelle contrainte avec CASCADE
        $this->addSql('ALTER TABLE fiche_muscles ADD CONSTRAINT FK_33497A21924AC07F FOREIGN KEY (fiche_contenu_id) REFERENCES fiche_contenu (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // Suppression de la contrainte avec CASCADE
        $this->addSql('ALTER TABLE fiche_muscles DROP FOREIGN KEY FK_33497A21924AC07F');
        
        // Retour à la contrainte originale
        $this->addSql('ALTER TABLE fiche_muscles ADD CONSTRAINT FK_33497A21924AC07F FOREIGN KEY (fiche_contenu_id) REFERENCES fiche_contenu (id)');
    }
}