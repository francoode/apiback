<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180209154151 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE note_business1 (note_id INT NOT NULL, business_id INT NOT NULL, INDEX IDX_BC7161D826ED0855 (note_id), INDEX IDX_BC7161D8A89DB457 (business_id), PRIMARY KEY(note_id, business_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_company1 (note_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_75ABD02126ED0855 (note_id), INDEX IDX_75ABD021979B1AD6 (company_id), PRIMARY KEY(note_id, company_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_contact1 (note_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_BBC9E95126ED0855 (note_id), INDEX IDX_BBC9E951E7A1254A (contact_id), PRIMARY KEY(note_id, contact_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note_business1 ADD CONSTRAINT FK_BC7161D826ED0855 FOREIGN KEY (note_id) REFERENCES note (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_business1 ADD CONSTRAINT FK_BC7161D8A89DB457 FOREIGN KEY (business_id) REFERENCES business (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_company1 ADD CONSTRAINT FK_75ABD02126ED0855 FOREIGN KEY (note_id) REFERENCES note (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_company1 ADD CONSTRAINT FK_75ABD021979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_contact1 ADD CONSTRAINT FK_BBC9E95126ED0855 FOREIGN KEY (note_id) REFERENCES note (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE note_contact1 ADD CONSTRAINT FK_BBC9E951E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE note_business');
        $this->addSql('DROP TABLE note_company');
        $this->addSql('DROP TABLE note_contact');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE note_business (business_id INT NOT NULL, note_id INT NOT NULL, INDEX IDX_1888B2A6A89DB457 (business_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_company (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, user_id INT DEFAULT NULL, study_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_CE19CDD5979B1AD6 (company_id), INDEX IDX_CE19CDD5A76ED395 (user_id), INDEX IDX_CE19CDD5E7B003E9 (study_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_contact (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, contact_id INT DEFAULT NULL, study_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_CDC422A2E7A1254A (contact_id), INDEX IDX_CDC422A2A76ED395 (user_id), INDEX IDX_CDC422A2E7B003E9 (study_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note_company ADD CONSTRAINT FK_CE19CDD5979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE note_company ADD CONSTRAINT FK_CE19CDD5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note_company ADD CONSTRAINT FK_CE19CDD5E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
        $this->addSql('ALTER TABLE note_contact ADD CONSTRAINT FK_CDC422A2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE note_contact ADD CONSTRAINT FK_CDC422A2E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE note_contact ADD CONSTRAINT FK_CDC422A2E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
        $this->addSql('DROP TABLE note_business1');
        $this->addSql('DROP TABLE note_company1');
        $this->addSql('DROP TABLE note_contact1');
    }
}
