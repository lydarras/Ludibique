<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221024104217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jeu_societe_auteur (jeu_societe_id INT NOT NULL, auteur_id INT NOT NULL, INDEX IDX_C267C58FD45F874B (jeu_societe_id), INDEX IDX_C267C58F60BB6FE6 (auteur_id), PRIMARY KEY(jeu_societe_id, auteur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jeu_societe_auteur ADD CONSTRAINT FK_C267C58FD45F874B FOREIGN KEY (jeu_societe_id) REFERENCES jeu_societe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jeu_societe_auteur ADD CONSTRAINT FK_C267C58F60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeu_societe_auteur DROP FOREIGN KEY FK_C267C58FD45F874B');
        $this->addSql('ALTER TABLE jeu_societe_auteur DROP FOREIGN KEY FK_C267C58F60BB6FE6');
        $this->addSql('DROP TABLE jeu_societe_auteur');
    }
}
