<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216131658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $hashedPassword = '$2y$13$JASCUchxHYtNVtNqLMJklOY/6ukjBIQIt87US5sfuubdM3OD2y5zO';
        $this->addSql(<<<SQL
        INSERT INTO `user`
            (cliente_id, username, login, password, email, is_active, is_admin, roles)
            VALUES(NULL, 'admin', 'admin', '$hashedPassword', 'admin@admin.com', 1, 1, '["ROLE_ADMIN"]');
        SQL
        );
    }

    public function down(Schema $schema): void
    {
    }
}
