<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225063100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create notifications table for forum notification system';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify to your needs
        // Only create notifications table if it doesn't exist
        if (!$schema->hasTable('notifications')) {
            $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, type VARCHAR(50) NOT NULL, message TEXT NOT NULL, related_discussion_id INT DEFAULT NULL, related_message_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type: datetime_immutable)\', is_read TINYINT(1) NOT NULL DEFAULT 0, INDEX IDX_52699625A76ED395 (user_id), INDEX IDX_5269962578D733FD (related_discussion_id), INDEX IDX_526996254B8E32A8 (related_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_52699625A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_5269962578D733FD FOREIGN KEY (related_discussion_id) REFERENCES forum_discussion (id) ON DELETE SET NULL');
            $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_526996254B8E32A8 FOREIGN KEY (related_message_id) REFERENCES forum_message (id) ON DELETE SET NULL');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_52699625A76ED395');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_5269962578D733FD');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_526996254B8E32A8');
        $this->addSql('DROP TABLE notifications');
    }
}
