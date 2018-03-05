<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180123154025 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE business (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, study_id INT DEFAULT NULL, pipeline_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, closingDate DATE NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_8D36E38979B1AD6 (company_id), INDEX IDX_8D36E38E7B003E9 (study_id), INDEX IDX_8D36E38E80B93 (pipeline_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE business_contact (business_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_11515D75A89DB457 (business_id), INDEX IDX_11515D75E7A1254A (contact_id), PRIMARY KEY(business_id, contact_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pipeline_state (id INT AUTO_INCREMENT NOT NULL, pipeline_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_8E63CB4E80B93 (pipeline_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pipeline (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7DFCD9D95E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE business ADD CONSTRAINT FK_8D36E38979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE business ADD CONSTRAINT FK_8D36E38E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
        $this->addSql('ALTER TABLE business ADD CONSTRAINT FK_8D36E38E80B93 FOREIGN KEY (pipeline_id) REFERENCES pipeline (id)');
        $this->addSql('ALTER TABLE business_contact ADD CONSTRAINT FK_11515D75A89DB457 FOREIGN KEY (business_id) REFERENCES business (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE business_contact ADD CONSTRAINT FK_11515D75E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pipeline_state ADD CONSTRAINT FK_8E63CB4E80B93 FOREIGN KEY (pipeline_id) REFERENCES pipeline (id)');
        $this->addSql('INSERT INTO pipeline (name) VALUES ("Venta")');
        $this->addSql('INSERT INTO pipeline_state (name, pipeline_id) VALUES ("Interesado", 1)');
        $this->addSql('INSERT INTO pipeline_state (name, pipeline_id) VALUES ("Presupuesto", 1)');
        $this->addSql('INSERT INTO pipeline_state (name, pipeline_id) VALUES ("Ganado", 1)');
        $this->addSql('INSERT INTO pipeline_state (name, pipeline_id) VALUES ("Perdido", 1)');
        $this->addSql('INSERT INTO pipeline_state (name, pipeline_id) VALUES ("Facturado", 1)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE business_contact DROP FOREIGN KEY FK_11515D75A89DB457');
        $this->addSql('ALTER TABLE business DROP FOREIGN KEY FK_8D36E38E80B93');
        $this->addSql('ALTER TABLE pipeline_state DROP FOREIGN KEY FK_8E63CB4E80B93');
        $this->addSql('DROP TABLE business');
        $this->addSql('DROP TABLE business_contact');
        $this->addSql('DROP TABLE pipeline_state');
        $this->addSql('DROP TABLE pipeline');
    }
}
