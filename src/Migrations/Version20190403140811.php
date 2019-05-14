<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190403140811 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agenda CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE clinica_id clinica_id INT DEFAULT NULL, CHANGE agenda_config_id agenda_config_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento TIME DEFAULT NULL, CHANGE horario_fim_atendimento horario_fim_atendimento TIME DEFAULT NULL, CHANGE data_inicio_atendimento data_inicio_atendimento DATE DEFAULT NULL, CHANGE data_fim_atendimento data_fim_atendimento DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE paciente CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clinica CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_config CHANGE agenda_id agenda_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_data CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE data_confirmacao data_confirmacao DATETIME DEFAULT NULL, CHANGE confirmacao_pelo_paciente confirmacao_pelo_paciente TINYINT(1) DEFAULT NULL, CHANGE confirmacao confirmacao TINYINT(1) DEFAULT \'0\', CHANGE pagamento_efetuado pagamento_efetuado TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE medico CHANGE cliente_id cliente_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agenda CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE clinica_id clinica_id INT DEFAULT NULL, CHANGE agenda_config_id agenda_config_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento TIME DEFAULT \'NULL\', CHANGE horario_fim_atendimento horario_fim_atendimento TIME DEFAULT \'NULL\', CHANGE data_inicio_atendimento data_inicio_atendimento DATE DEFAULT \'NULL\', CHANGE data_fim_atendimento data_fim_atendimento DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE agenda_config CHANGE agenda_id agenda_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_data CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE data_confirmacao data_confirmacao DATETIME DEFAULT \'NULL\', CHANGE confirmacao_pelo_paciente confirmacao_pelo_paciente TINYINT(1) DEFAULT \'NULL\', CHANGE confirmacao confirmacao TINYINT(1) DEFAULT \'0\', CHANGE pagamento_efetuado pagamento_efetuado TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE clinica CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medico CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paciente CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
