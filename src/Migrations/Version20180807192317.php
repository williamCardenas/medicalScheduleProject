<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180807192317 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agenda_data CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE clinica CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_config CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE valor_consulta valor_consulta DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE medico CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE clinica_id clinica_id INT DEFAULT NULL, CHANGE agenda_config_id agenda_config_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento TIME DEFAULT NULL, CHANGE horario_fim_atendimento horario_fim_atendimento TIME DEFAULT NULL, CHANGE data_inicio_atendimento data_inicio_atendimento DATE DEFAULT NULL, CHANGE data_fim_atendimento data_fim_atendimento DATE DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agenda CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE clinica_id clinica_id INT DEFAULT NULL, CHANGE agenda_config_id agenda_config_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento TIME DEFAULT \'NULL\', CHANGE horario_fim_atendimento horario_fim_atendimento TIME DEFAULT \'NULL\', CHANGE data_inicio_atendimento data_inicio_atendimento DATE DEFAULT \'NULL\', CHANGE data_fim_atendimento data_fim_atendimento DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE agenda_config CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE valor_consulta valor_consulta INT NOT NULL');
        $this->addSql('ALTER TABLE agenda_data CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clinica CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medico CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
