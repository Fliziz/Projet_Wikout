<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219131704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_33497A212B48856B ON fiche_muscles');
        $this->addSql('DROP INDEX UNIQ_33497A21924AC07F ON fiche_muscles');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_33497A212B48856B ON fiche_muscles (muscles_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_33497A21924AC07F ON fiche_muscles (fiche_contenu_id)');
        $this->addSql('ALTER TABLE fiches DROP FOREIGN KEY FK_459C25C9FB88E14F');
        $this->addSql('ALTER TABLE fiches ADD CONSTRAINT FK_459C25C9FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiches DROP FOREIGN KEY FK_459C25C9FB88E14F');
        $this->addSql('ALTER TABLE fiches ADD CONSTRAINT FK_459C25C9FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('DROP INDEX UNIQ_33497A21924AC07F ON fiche_muscles');
        $this->addSql('DROP INDEX UNIQ_33497A212B48856B ON fiche_muscles');
        $this->addSql('CREATE INDEX UNIQ_33497A21924AC07F ON fiche_muscles (fiche_contenu_id)');
        $this->addSql('CREATE INDEX UNIQ_33497A212B48856B ON fiche_muscles (muscles_id)');
    }
}
