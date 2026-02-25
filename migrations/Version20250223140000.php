<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250223140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create participation table (étudiants rejoignent les événements)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE participation (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            event_id INT NOT NULL,
            date_inscription DATETIME NOT NULL,
            UNIQUE INDEX unique_user_event (user_id, event_id),
            INDEX IDX_AB55E24FA76ED395 (user_id),
            INDEX IDX_AB55E24F71F7E947 (event_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F71F7E947 FOREIGN KEY (event_id) REFERENCES event (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FA76ED395');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F71F7E947');
        $this->addSql('DROP TABLE participation');
    }
}
