<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181016200947 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paciente ADD cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE paciente ADD CONSTRAINT FK_C6CBA95EDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('CREATE INDEX IDX_C6CBA95EDE734E51 ON paciente (cliente_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paciente DROP FOREIGN KEY FK_C6CBA95EDE734E51');
        $this->addSql('DROP INDEX IDX_C6CBA95EDE734E51 ON paciente');
        $this->addSql('ALTER TABLE paciente DROP cliente_id');
    }
}
