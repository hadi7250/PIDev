<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260208175118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, link VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY `FK_CHAPITRE_COURS`');
        $this->addSql('ALTER TABLE chapitre DROP created_at, CHANGE id_cours id_cours INT NOT NULL');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT FK_8C62B025134FCDAC FOREIGN KEY (id_cours) REFERENCES cours (id_cours)');
        $this->addSql('ALTER TABLE chapitre RENAME INDEX idx_chapitre_cours TO IDX_8C62B025134FCDAC');
        $this->addSql('ALTER TABLE cours CHANGE created_at created_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE chapitre DROP FOREIGN KEY FK_8C62B025134FCDAC');
        $this->addSql('ALTER TABLE chapitre ADD created_at DATETIME DEFAULT \'current_timestamp()\' NOT NULL, CHANGE id_cours id_cours INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chapitre ADD CONSTRAINT `FK_CHAPITRE_COURS` FOREIGN KEY (id_cours) REFERENCES cours (id_cours) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chapitre RENAME INDEX idx_8c62b025134fcdac TO IDX_CHAPITRE_COURS');
        $this->addSql('ALTER TABLE cours CHANGE created_at created_at DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
    }
}
