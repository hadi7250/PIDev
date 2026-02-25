<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250223120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create rating and certificat tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE rating (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            event_id INT NOT NULL,
            note SMALLINT NOT NULL,
            commentaire LONGTEXT DEFAULT NULL,
            date_creation DATETIME NOT NULL,
            INDEX IDX_A3B606FCA76ED395 (user_id),
            INDEX IDX_A3B606FC71F7E947 (event_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certificat (
            id INT AUTO_INCREMENT NOT NULL,
            user_id INT NOT NULL,
            event_id INT NOT NULL,
            date_obtention DATETIME NOT NULL,
            code_unique VARCHAR(50) NOT NULL,
            UNIQUE INDEX UNIQ_8E2A723B6F9F26F4 (code_unique),
            INDEX IDX_8E2A723BA76ED395 (user_id),
            INDEX IDX_8E2A723B71F7E947 (event_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_A3B606FCA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_A3B606FC71F7E947 FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE certificat ADD CONSTRAINT FK_8E2A723BA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE certificat ADD CONSTRAINT FK_8E2A723B71F7E947 FOREIGN KEY (event_id) REFERENCES event (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_A3B606FCA76ED395');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_A3B606FC71F7E947');
        $this->addSql('ALTER TABLE certificat DROP FOREIGN KEY FK_8E2A723BA76ED395');
        $this->addSql('ALTER TABLE certificat DROP FOREIGN KEY FK_8E2A723B71F7E947');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE certificat');
    }
}
