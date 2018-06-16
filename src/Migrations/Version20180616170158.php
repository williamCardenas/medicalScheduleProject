<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180616170158 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE paciente (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, apelido VARCHAR(255) NOT NULL, idade INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agenda_config (id INT AUTO_INCREMENT NOT NULL, valor_consulta INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agenda_data (id INT AUTO_INCREMENT NOT NULL, agenda_id INT DEFAULT NULL, paciente_id INT DEFAULT NULL, user_id INT DEFAULT NULL, data_consulta DATETIME NOT NULL, data_confirmacao DATETIME NOT NULL, confirmacao_pelo_paciente TINYINT(1) NOT NULL, confirmacao TINYINT(1) NOT NULL, pagamento_efetuado TINYINT(1) NOT NULL, data_atualizacao DATETIME NOT NULL, INDEX IDX_90D65113EA67784A (agenda_id), INDEX IDX_90D651137310DAD4 (paciente_id), INDEX IDX_90D65113A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agenda (id INT AUTO_INCREMENT NOT NULL, medico_id INT DEFAULT NULL, agenda_id INT DEFAULT NULL, horario_inicio_atendimento DATETIME DEFAULT NULL, horario_fim_atendimento DATETIME DEFAULT NULL, fim_de_semana TINYINT(1) NOT NULL, INDEX IDX_2CEDC877A7FB1C0C (medico_id), UNIQUE INDEX UNIQ_2CEDC877EA67784A (agenda_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medico (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, numero_documento VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agenda_data ADD CONSTRAINT FK_90D65113EA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id)');
        $this->addSql('ALTER TABLE agenda_data ADD CONSTRAINT FK_90D651137310DAD4 FOREIGN KEY (paciente_id) REFERENCES paciente (id)');
        $this->addSql('ALTER TABLE agenda_data ADD CONSTRAINT FK_90D65113A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC877A7FB1C0C FOREIGN KEY (medico_id) REFERENCES medico (id)');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC877EA67784A FOREIGN KEY (agenda_id) REFERENCES agenda_config (id)');
        $this->addSql('ALTER TABLE user CHANGE client_id client_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE agenda_data DROP FOREIGN KEY FK_90D651137310DAD4');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC877EA67784A');
        $this->addSql('ALTER TABLE agenda_data DROP FOREIGN KEY FK_90D65113EA67784A');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC877A7FB1C0C');
        $this->addSql('DROP TABLE paciente');
        $this->addSql('DROP TABLE agenda_config');
        $this->addSql('DROP TABLE agenda_data');
        $this->addSql('DROP TABLE agenda');
        $this->addSql('DROP TABLE medico');
        $this->addSql('ALTER TABLE user CHANGE client_id client_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
