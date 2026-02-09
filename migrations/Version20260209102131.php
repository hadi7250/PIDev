<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209102131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__competence AS SELECT id, name, description, category, max_level FROM competence');
        $this->addSql('DROP TABLE competence');
        $this->addSql('CREATE TABLE competence (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, category VARCHAR(100) NOT NULL, max_level INTEGER DEFAULT 5 NOT NULL)');
        $this->addSql('INSERT INTO competence (id, name, description, category, max_level) SELECT id, name, description, category, max_level FROM __temp__competence');
        $this->addSql('DROP TABLE __temp__competence');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_94D4687F5E237E06 ON competence (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__competence AS SELECT id, name, description, category, max_level FROM competence');
        $this->addSql('DROP TABLE competence');
        $this->addSql('CREATE TABLE competence (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, category VARCHAR(100) NOT NULL, max_level INTEGER NOT NULL)');
        $this->addSql('INSERT INTO competence (id, name, description, category, max_level) SELECT id, name, description, category, max_level FROM __temp__competence');
        $this->addSql('DROP TABLE __temp__competence');
    }
}
