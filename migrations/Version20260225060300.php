<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225060300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add foreign key constraint to parent_id column';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // Check if foreign key constraint already exists to avoid duplicate key error
        $table = $schema->getTable('forum_message');
        
        // Only add foreign key if it doesn't exist
        if (!$table->hasForeignKey('FK_6FBC284727D8E0F')) {
            $this->addSql('ALTER TABLE forum_message ADD CONSTRAINT FK_6FBC284727D8E0F FOREIGN KEY (parent_id) REFERENCES forum_message (id) ON DELETE CASCADE');
        }
        
        // Only add index if it doesn't exist
        if (!$table->hasIndex('IDX_6FBC284727D8E0F')) {
            $this->addSql('CREATE INDEX IDX_6FBC284727D8E0F ON forum_message (parent_id)');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum_message DROP FOREIGN KEY FK_6FBC284727D8E0F');
        $this->addSql('DROP INDEX IDX_6FBC284727D8E0F ON forum_message');
    }
}
