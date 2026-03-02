<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225041400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add views column to forum_discussion table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Check if views column already exists to avoid duplicate column error
        $table = $schema->getTable('forum_discussion');
        if (!$table->hasColumn('views')) {
            $this->addSql('ALTER TABLE forum_discussion ADD views INT DEFAULT 0 NOT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum_discussion DROP views');
    }
}
