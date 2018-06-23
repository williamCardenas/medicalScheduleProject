<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180623155128 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64919EB6921');
        $this->addSql('CREATE TABLE clinica (id INT AUTO_INCREMENT NOT NULL, clinica_id INT DEFAULT NULL, nome VARCHAR(255) NOT NULL, INDEX IDX_24BC4A2E9CD3F6D6 (clinica_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cliente (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_F41C9B255E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clinica ADD CONSTRAINT FK_24BC4A2E9CD3F6D6 FOREIGN KEY (clinica_id) REFERENCES cliente (id)');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE clinic');
        $this->addSql('DROP INDEX IDX_8D93D64919EB6921 ON user');
        $this->addSql('ALTER TABLE user ADD cliente_id INT DEFAULT NULL, DROP client_id, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649DE734E51 ON user (cliente_id)');
        $this->addSql('ALTER TABLE agenda_data CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento DATETIME DEFAULT NULL, CHANGE horario_fim_atendimento horario_fim_atendimento DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DE734E51');
        $this->addSql('ALTER TABLE clinica DROP FOREIGN KEY FK_24BC4A2E9CD3F6D6');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_C74404555E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clinic (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, client_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE clinica');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('ALTER TABLE agenda CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento DATETIME DEFAULT \'NULL\', CHANGE horario_fim_atendimento horario_fim_atendimento DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE agenda_data CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_8D93D649DE734E51 ON user');
        $this->addSql('ALTER TABLE user ADD client_id INT DEFAULT NULL, DROP cliente_id, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64919EB6921 ON user (client_id)');
    }
}
