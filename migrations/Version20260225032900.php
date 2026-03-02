<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225032900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add attachment_name column to forum_discussion table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify to your needs
        // Check if attachment_name column already exists to avoid duplicate column error
        $table = $schema->getTable('forum_discussion');
        if (!$table->hasColumn('attachment_name')) {
            $this->addSql('ALTER TABLE forum_discussion ADD attachment_name VARCHAR(255) DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum_discussion DROP attachment_name');
    }
}
