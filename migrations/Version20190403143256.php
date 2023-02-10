<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190403143256 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    
        $this->addSql('ALTER TABLE `agenda` 
        DROP FOREIGN KEY `FK_2CEDC8776E05FB77`;
        ALTER TABLE `agenda` 
        ADD CONSTRAINT `FK_2CEDC8776E05FB77`
          FOREIGN KEY (`agenda_config_id`)
          REFERENCES `agenda_config` (`id`)
          ON DELETE CASCADE
          ON UPDATE CASCADE
        ');
         $this->addSql('ALTER TABLE `agenda_config` 
         DROP FOREIGN KEY `FK_C36DA96BEA67784A`;
         ALTER TABLE `agenda_config` 
         ADD CONSTRAINT `FK_C36DA96BEA67784A`
           FOREIGN KEY (`agenda_id`)
           REFERENCES `agenda` (`id`)
           ON DELETE CASCADE
           ON UPDATE CASCADE;
         
         
         ');
         
        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
