<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180111152656 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE company_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CFB34DC75E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company ADD type_id INT DEFAULT NULL, DROP type');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FC54C8C93 FOREIGN KEY (type_id) REFERENCES company_type (id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FC54C8C93 ON company (type_id)');
        $this->addSql('INSERT INTO company_type (name) VALUES ("Sociedad Anonima")');
        $this->addSql('INSERT INTO company_type (name) VALUES ("SRL")');
        $this->addSql('INSERT INTO company_type (name) VALUES ("Cooperativa")');
        $this->addSql('INSERT INTO company_type (name) VALUES ("Unipersonal")');
        $this->addSql('INSERT INTO company_type (name) VALUES ("SAS")');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FC54C8C93');
        $this->addSql('DROP TABLE company_type');
        $this->addSql('DROP INDEX IDX_4FBF094FC54C8C93 ON company');
        $this->addSql('ALTER TABLE company ADD type VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP type_id');
    }
}
