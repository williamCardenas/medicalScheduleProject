<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180801175707 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cliente DROP FOREIGN KEY FK_F41C9B25A7FB1C0C');
        $this->addSql('DROP INDEX IDX_F41C9B25A7FB1C0C ON cliente');
        $this->addSql('ALTER TABLE cliente DROP medico_id');
        $this->addSql('ALTER TABLE medico ADD cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medico ADD CONSTRAINT FK_34E5914CDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('CREATE INDEX IDX_34E5914CDE734E51 ON medico (cliente_id)');
        $this->addSql('ALTER TABLE clinica CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE agenda CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento DATETIME DEFAULT NULL, CHANGE horario_fim_atendimento horario_fim_atendimento DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_data CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agenda CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento DATETIME DEFAULT \'NULL\', CHANGE horario_fim_atendimento horario_fim_atendimento DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE agenda_data CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cliente ADD medico_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cliente ADD CONSTRAINT FK_F41C9B25A7FB1C0C FOREIGN KEY (medico_id) REFERENCES medico (id)');
        $this->addSql('CREATE INDEX IDX_F41C9B25A7FB1C0C ON cliente (medico_id)');
        $this->addSql('ALTER TABLE clinica CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medico DROP FOREIGN KEY FK_34E5914CDE734E51');
        $this->addSql('DROP INDEX IDX_34E5914CDE734E51 ON medico');
        $this->addSql('ALTER TABLE medico DROP cliente_id');
        $this->addSql('ALTER TABLE user CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
