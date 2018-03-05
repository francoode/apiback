<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180228125934 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE log_type_manual (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_6FB5A9545E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE log_action ADD type_manual INT DEFAULT NULL');
        $this->addSql('ALTER TABLE log_action ADD CONSTRAINT FK_5236DF30330CD5FC FOREIGN KEY (type_manual) REFERENCES log_type_manual (id)');
        $this->addSql('CREATE INDEX IDX_5236DF30330CD5FC ON log_action (type_manual)');
        $this->addSql('INSERT INTO log_type_manual (name) VALUES ("Llamada")');
        $this->addSql('INSERT INTO log_type_manual (name) VALUES ("Correo")');
        $this->addSql('INSERT INTO log_type_manual (name) VALUES ("Reunión")');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE log_action DROP FOREIGN KEY FK_5236DF30330CD5FC');
        $this->addSql('DROP TABLE log_type_manual');
        $this->addSql('DROP INDEX IDX_5236DF30330CD5FC ON log_action');
        $this->addSql('ALTER TABLE log_action DROP type_manual');
    }
}
