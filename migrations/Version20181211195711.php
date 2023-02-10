<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181211195711 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agenda_data CHANGE data_confirmacao data_confirmacao DATETIME DEFAULT NULL, CHANGE confirmacao_pelo_paciente confirmacao_pelo_paciente TINYINT(1) DEFAULT NULL, CHANGE confirmacao confirmacao TINYINT(1) DEFAULT \'0\', CHANGE pagamento_efetuado pagamento_efetuado TINYINT(1) DEFAULT \'0\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agenda_data CHANGE data_confirmacao data_confirmacao DATETIME NOT NULL, CHANGE confirmacao_pelo_paciente confirmacao_pelo_paciente TINYINT(1) NOT NULL, CHANGE confirmacao confirmacao TINYINT(1) NOT NULL, CHANGE pagamento_efetuado pagamento_efetuado TINYINT(1) NOT NULL');
    }
}
