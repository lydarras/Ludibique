<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213140559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FA9E2D76C');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FE3797A94');
        $this->addSql('DROP TABLE participation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, joueur_id INT DEFAULT NULL, seance_id INT DEFAULT NULL, INDEX IDX_AB55E24FA9E2D76C (joueur_id), INDEX IDX_AB55E24FE3797A94 (seance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FA9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FE3797A94 FOREIGN KEY (seance_id) REFERENCES seance (id)');
    }
}
