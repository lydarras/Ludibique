<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221020131647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cree (id INT AUTO_INCREMENT NOT NULL, auteur_id INT DEFAULT NULL, jeu_societe_id INT DEFAULT NULL, INDEX IDX_7AF031FA60BB6FE6 (auteur_id), INDEX IDX_7AF031FAD45F874B (jeu_societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cree ADD CONSTRAINT FK_7AF031FA60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id)');
        $this->addSql('ALTER TABLE cree ADD CONSTRAINT FK_7AF031FAD45F874B FOREIGN KEY (jeu_societe_id) REFERENCES jeu_societe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cree DROP FOREIGN KEY FK_7AF031FA60BB6FE6');
        $this->addSql('ALTER TABLE cree DROP FOREIGN KEY FK_7AF031FAD45F874B');
        $this->addSql('DROP TABLE cree');
    }
}
