<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180807165504 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agenda_config ADD agenda_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_config ADD CONSTRAINT FK_C36DA96BEA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C36DA96BEA67784A ON agenda_config (agenda_id)');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC877EA67784A');
        $this->addSql('DROP INDEX UNIQ_2CEDC877EA67784A ON agenda');
        $this->addSql('ALTER TABLE agenda ADD agenda_config_id INT DEFAULT NULL, DROP agenda_id, CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE clinica_id clinica_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento TIME DEFAULT NULL, CHANGE horario_fim_atendimento horario_fim_atendimento TIME DEFAULT NULL, CHANGE data_inicio_atendimento data_inicio_atendimento DATE DEFAULT NULL, CHANGE data_fim_atendimento data_fim_atendimento DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC8776E05FB77 FOREIGN KEY (agenda_config_id) REFERENCES agenda_config (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CEDC8776E05FB77 ON agenda (agenda_config_id)');
        $this->addSql('ALTER TABLE agenda_data CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE clinica CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medico CHANGE cliente_id cliente_id INT DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC8776E05FB77');
        $this->addSql('DROP INDEX UNIQ_2CEDC8776E05FB77 ON agenda');
        $this->addSql('ALTER TABLE agenda ADD agenda_id INT DEFAULT NULL, DROP agenda_config_id, CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE clinica_id clinica_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento TIME DEFAULT \'NULL\', CHANGE horario_fim_atendimento horario_fim_atendimento TIME DEFAULT \'NULL\', CHANGE data_inicio_atendimento data_inicio_atendimento DATE DEFAULT \'NULL\', CHANGE data_fim_atendimento data_fim_atendimento DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC877EA67784A FOREIGN KEY (agenda_id) REFERENCES agenda_config (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CEDC877EA67784A ON agenda (agenda_id)');
        $this->addSql('ALTER TABLE agenda_config DROP FOREIGN KEY FK_C36DA96BEA67784A');
        $this->addSql('DROP INDEX UNIQ_C36DA96BEA67784A ON agenda_config');
        $this->addSql('ALTER TABLE agenda_config DROP agenda_id');
        $this->addSql('ALTER TABLE agenda_data CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clinica CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medico CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
