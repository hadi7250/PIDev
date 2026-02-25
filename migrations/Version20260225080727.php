<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225080727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create chat_message table for chatbot conversations';
    }

    public function up(Schema $schema): void
    {
        // Skip if table already exists
        if ($schema->hasTable('chat_message')) {
            return;
        }
        
        $this->addSql('CREATE TABLE chat_message (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_message CLOB NOT NULL, bot_response CLOB NOT NULL, language VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)');
        $this->addSql('CREATE INDEX idx_language ON chat_message (language)');
        $this->addSql('CREATE INDEX idx_created_at ON chat_message (created_at)');
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('chat_message')) {
            $this->addSql('DROP TABLE chat_message');
        }
    }
}
