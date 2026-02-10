<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209145620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE attempt DROP FOREIGN KEY `fk_attempt_quiz`');
        $this->addSql('ALTER TABLE attempt CHANGE completed_at completed_at DATETIME DEFAULT NULL, CHANGE answers answers JSON NOT NULL');
        $this->addSql('ALTER TABLE attempt ADD CONSTRAINT FK_18EC0266853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE attempt RENAME INDEX fk_attempt_quiz TO IDX_18EC0266853CD175');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY `fk_question_quiz`');
        $this->addSql('ALTER TABLE question CHANGE question_text question_text VARCHAR(255) NOT NULL, CHANGE options options JSON NOT NULL, CHANGE correct_answer correct_answer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE question RENAME INDEX fk_question_quiz TO IDX_B6F7494E853CD175');
        $this->addSql('ALTER TABLE quiz CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE time_limit time_limit INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE attempt DROP FOREIGN KEY FK_18EC0266853CD175');
        $this->addSql('ALTER TABLE attempt CHANGE completed_at completed_at DATETIME DEFAULT \'NULL\', CHANGE answers answers LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE attempt ADD CONSTRAINT `fk_attempt_quiz` FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attempt RENAME INDEX idx_18ec0266853cd175 TO fk_attempt_quiz');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E853CD175');
        $this->addSql('ALTER TABLE question CHANGE question_text question_text TEXT NOT NULL, CHANGE options options LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE correct_answer correct_answer LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT `fk_question_quiz` FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question RENAME INDEX idx_b6f7494e853cd175 TO fk_question_quiz');
        $this->addSql('ALTER TABLE quiz CHANGE description description TEXT DEFAULT NULL, CHANGE time_limit time_limit INT NOT NULL');
    }
}
