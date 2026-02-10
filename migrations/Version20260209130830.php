<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209130830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE forum_category (id_forum_category INT AUTO_INCREMENT NOT NULL, forum_category_name VARCHAR(255) NOT NULL, forum_category_description LONGTEXT DEFAULT NULL, forum_category_color VARCHAR(7) DEFAULT NULL, forum_category_created_at DATETIME NOT NULL, forum_category_updated_at DATETIME DEFAULT NULL, PRIMARY KEY (id_forum_category)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE forum_discussion (id_forum_discussion INT AUTO_INCREMENT NOT NULL, forum_discussion_title VARCHAR(255) NOT NULL, forum_discussion_content LONGTEXT NOT NULL, forum_discussion_author_name VARCHAR(255) NOT NULL, forum_discussion_author_email VARCHAR(255) DEFAULT NULL, forum_discussion_is_pinned TINYINT NOT NULL, forum_discussion_is_locked TINYINT NOT NULL, forum_discussion_views INT NOT NULL, forum_discussion_created_at DATETIME NOT NULL, forum_discussion_updated_at DATETIME DEFAULT NULL, id_forum_category INT DEFAULT NULL, INDEX IDX_428F444A1CD92920 (id_forum_category), PRIMARY KEY (id_forum_discussion)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE forum_message (id_forum_message INT AUTO_INCREMENT NOT NULL, forum_message_content LONGTEXT NOT NULL, forum_message_author_name VARCHAR(255) NOT NULL, forum_message_author_email VARCHAR(255) DEFAULT NULL, forum_message_is_author TINYINT NOT NULL, forum_message_created_at DATETIME NOT NULL, forum_message_updated_at DATETIME DEFAULT NULL, id_forum_discussion INT NOT NULL, INDEX IDX_47717D0EA105119B (id_forum_discussion), PRIMARY KEY (id_forum_message)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE forum_discussion ADD CONSTRAINT FK_428F444A1CD92920 FOREIGN KEY (id_forum_category) REFERENCES forum_category (id_forum_category)');
        $this->addSql('ALTER TABLE forum_message ADD CONSTRAINT FK_47717D0EA105119B FOREIGN KEY (id_forum_discussion) REFERENCES forum_discussion (id_forum_discussion)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE forum_discussion DROP FOREIGN KEY FK_428F444A1CD92920');
        $this->addSql('ALTER TABLE forum_message DROP FOREIGN KEY FK_47717D0EA105119B');
        $this->addSql('DROP TABLE forum_category');
        $this->addSql('DROP TABLE forum_discussion');
        $this->addSql('DROP TABLE forum_message');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
