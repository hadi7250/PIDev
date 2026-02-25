<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250223130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Attribuer ROLE_ADMIN au compte admin@educonnect.com';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE `user` SET roles = '[\"ROLE_ADMIN\"]' WHERE email = 'admin@educonnect.com'");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE `user` SET roles = '[]' WHERE email = 'admin@educonnect.com'");
    }
}
