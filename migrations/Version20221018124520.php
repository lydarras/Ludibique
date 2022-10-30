<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221018124520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jeu_societe (id INT AUTO_INCREMENT NOT NULL, editeur_id INT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, age_min SMALLINT NOT NULL, nb_joueur_min SMALLINT NOT NULL, nb_joueur_max SMALLINT NOT NULL, duree_estimee INT NOT NULL, INDEX IDX_5C0935573375BD21 (editeur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jeu_societe ADD CONSTRAINT FK_5C0935573375BD21 FOREIGN KEY (editeur_id) REFERENCES editeur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeu_societe DROP FOREIGN KEY FK_5C0935573375BD21');
        $this->addSql('DROP TABLE jeu_societe');
    }
}
