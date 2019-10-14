<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180801172932 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clinica CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE agenda CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento DATETIME DEFAULT NULL, CHANGE horario_fim_atendimento horario_fim_atendimento DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_data CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agenda CHANGE medico_id medico_id INT DEFAULT NULL, CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE horario_inicio_atendimento horario_inicio_atendimento DATETIME DEFAULT \'NULL\', CHANGE horario_fim_atendimento horario_fim_atendimento DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE agenda_data CHANGE agenda_id agenda_id INT DEFAULT NULL, CHANGE paciente_id paciente_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clinica CHANGE cliente_id cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE cliente_id cliente_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
