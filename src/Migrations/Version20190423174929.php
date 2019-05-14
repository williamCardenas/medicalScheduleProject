<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190423174929 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE agenda_data_status (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A822B4C354BD530C (nome), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('INSERT INTO agenda_data_status (nome) VALUES ("agendado"),("reagendado"),("cancelado"),("atendido")');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC8776E05FB77');
        $this->addSql('ALTER TABLE agenda CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE clinica_id clinica_id INT DEFAULT NULL, CHANGE agenda_config_id agenda_config_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento TIME DEFAULT NULL, CHANGE horario_fim_atendimento horario_fim_atendimento TIME DEFAULT NULL, CHANGE data_inicio_atendimento data_inicio_atendimento DATE DEFAULT NULL, CHANGE data_fim_atendimento data_fim_atendimento DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC8776E05FB77 FOREIGN KEY (agenda_config_id) REFERENCES agenda_config (id)');
        $this->addSql('ALTER TABLE user CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE paciente CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clinica CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_config DROP FOREIGN KEY FK_C36DA96BEA67784A');
        $this->addSql('ALTER TABLE agenda_config CHANGE agenda_id agenda_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_config ADD CONSTRAINT FK_C36DA96BEA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id)');
        $this->addSql('ALTER TABLE agenda_data ADD status INT DEFAULT NULL, CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE data_confirmacao data_confirmacao DATETIME DEFAULT NULL, CHANGE confirmacao_pelo_paciente confirmacao_pelo_paciente TINYINT(1) DEFAULT NULL, CHANGE confirmacao confirmacao TINYINT(1) DEFAULT \'0\', CHANGE pagamento_efetuado pagamento_efetuado TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE agenda_data ADD CONSTRAINT FK_90D651137B00651C FOREIGN KEY (status) REFERENCES agenda_data_status (id)');
        $this->addSql('CREATE INDEX IDX_90D651137B00651C ON agenda_data (status)');
        $this->addSql('ALTER TABLE medico CHANGE cliente_id cliente_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agenda_data DROP FOREIGN KEY FK_90D651137B00651C');
        $this->addSql('DROP TABLE agenda_data_status');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC8776E05FB77');
        $this->addSql('ALTER TABLE agenda CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE clinica_id clinica_id INT DEFAULT NULL, CHANGE agenda_config_id agenda_config_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento TIME DEFAULT \'NULL\', CHANGE horario_fim_atendimento horario_fim_atendimento TIME DEFAULT \'NULL\', CHANGE data_inicio_atendimento data_inicio_atendimento DATE DEFAULT \'NULL\', CHANGE data_fim_atendimento data_fim_atendimento DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC8776E05FB77 FOREIGN KEY (agenda_config_id) REFERENCES agenda_config (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE agenda_config DROP FOREIGN KEY FK_C36DA96BEA67784A');
        $this->addSql('ALTER TABLE agenda_config CHANGE agenda_id agenda_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_config ADD CONSTRAINT FK_C36DA96BEA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX IDX_90D651137B00651C ON agenda_data');
        $this->addSql('ALTER TABLE agenda_data DROP status, CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE data_confirmacao data_confirmacao DATETIME DEFAULT \'NULL\', CHANGE confirmacao_pelo_paciente confirmacao_pelo_paciente TINYINT(1) DEFAULT \'NULL\', CHANGE confirmacao confirmacao TINYINT(1) DEFAULT \'0\', CHANGE pagamento_efetuado pagamento_efetuado TINYINT(1) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE clinica CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medico CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paciente CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
