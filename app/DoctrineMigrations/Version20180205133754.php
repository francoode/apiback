<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180205133754 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE note_business (id INT AUTO_INCREMENT NOT NULL, business_id INT DEFAULT NULL, user_id INT DEFAULT NULL, study_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_1888B2A6A89DB457 (business_id), INDEX IDX_1888B2A6A76ED395 (user_id), INDEX IDX_1888B2A6E7B003E9 (study_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_contact (id INT AUTO_INCREMENT NOT NULL, contact_id INT DEFAULT NULL, user_id INT DEFAULT NULL, study_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_CDC422A2E7A1254A (contact_id), INDEX IDX_CDC422A2A76ED395 (user_id), INDEX IDX_CDC422A2E7B003E9 (study_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_company (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, user_id INT DEFAULT NULL, study_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_CE19CDD5979B1AD6 (company_id), INDEX IDX_CE19CDD5A76ED395 (user_id), INDEX IDX_CE19CDD5E7B003E9 (study_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note_business ADD CONSTRAINT FK_1888B2A6A89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('ALTER TABLE note_business ADD CONSTRAINT FK_1888B2A6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note_business ADD CONSTRAINT FK_1888B2A6E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
        $this->addSql('ALTER TABLE note_contact ADD CONSTRAINT FK_CDC422A2E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE note_contact ADD CONSTRAINT FK_CDC422A2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note_contact ADD CONSTRAINT FK_CDC422A2E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
        $this->addSql('ALTER TABLE note_company ADD CONSTRAINT FK_CE19CDD5979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE note_company ADD CONSTRAINT FK_CE19CDD5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note_company ADD CONSTRAINT FK_CE19CDD5E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE note_business');
        $this->addSql('DROP TABLE note_contact');
        $this->addSql('DROP TABLE note_company');
    }
}
