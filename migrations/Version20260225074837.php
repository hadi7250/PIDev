<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225074837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evaluation_exercise (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, language VARCHAR(50) NOT NULL, template_code CLOB NOT NULL, solution_code CLOB NOT NULL, description CLOB DEFAULT NULL, hint CLOB DEFAULT NULL, difficulty INTEGER NOT NULL, created_at DATETIME NOT NULL, evaluation_id INTEGER NOT NULL, CONSTRAINT FK_A394AC3A456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A394AC3A456C5646 ON evaluation_exercise (evaluation_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__evaluation AS SELECT id, title, description, type, evaluation_date, weight, competence_id FROM evaluation');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('CREATE TABLE evaluation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, type VARCHAR(50) NOT NULL, evaluation_date DATETIME NOT NULL, weight DOUBLE PRECISION DEFAULT NULL, competence_id INTEGER NOT NULL, language VARCHAR(50) DEFAULT NULL, CONSTRAINT FK_1323A57515761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO evaluation (id, title, description, type, evaluation_date, weight, competence_id) SELECT id, title, description, type, evaluation_date, weight, competence_id FROM __temp__evaluation');
        $this->addSql('DROP TABLE __temp__evaluation');
        $this->addSql('CREATE INDEX IDX_1323A57515761DAB ON evaluation (competence_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE evaluation_exercise');
        $this->addSql('CREATE TEMPORARY TABLE __temp__evaluation AS SELECT id, title, description, type, evaluation_date, weight, competence_id FROM evaluation');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('CREATE TABLE evaluation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, type VARCHAR(50) NOT NULL, evaluation_date DATETIME NOT NULL, weight DOUBLE PRECISION DEFAULT NULL, competence_id INTEGER NOT NULL, status VARCHAR(20) DEFAULT NULL, score DOUBLE PRECISION DEFAULT NULL, date DATETIME DEFAULT NULL, CONSTRAINT FK_1323A57515761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO evaluation (id, title, description, type, evaluation_date, weight, competence_id) SELECT id, title, description, type, evaluation_date, weight, competence_id FROM __temp__evaluation');
        $this->addSql('DROP TABLE __temp__evaluation');
        $this->addSql('CREATE INDEX IDX_1323A57515761DAB ON evaluation (competence_id)');
    }
}
