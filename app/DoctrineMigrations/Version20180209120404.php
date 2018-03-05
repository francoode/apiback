<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180209120404 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE log_action (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, study_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, INDEX IDX_5236DF30A76ED395 (user_id), INDEX IDX_5236DF30E7B003E9 (study_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE logaction_business (log_action_id INT NOT NULL, business_id INT NOT NULL, INDEX IDX_E52172878B306CBB (log_action_id), INDEX IDX_E5217287A89DB457 (business_id), PRIMARY KEY(log_action_id, business_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE logaction_company (log_action_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_52096CF8B306CBB (log_action_id), INDEX IDX_52096CF979B1AD6 (company_id), PRIMARY KEY(log_action_id, company_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE logaction_contact (log_action_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_6FD79B88B306CBB (log_action_id), INDEX IDX_6FD79B8E7A1254A (contact_id), PRIMARY KEY(log_action_id, contact_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE log_action ADD CONSTRAINT FK_5236DF30A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE log_action ADD CONSTRAINT FK_5236DF30E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
        $this->addSql('ALTER TABLE logaction_business ADD CONSTRAINT FK_E52172878B306CBB FOREIGN KEY (log_action_id) REFERENCES log_action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE logaction_business ADD CONSTRAINT FK_E5217287A89DB457 FOREIGN KEY (business_id) REFERENCES business (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE logaction_company ADD CONSTRAINT FK_52096CF8B306CBB FOREIGN KEY (log_action_id) REFERENCES log_action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE logaction_company ADD CONSTRAINT FK_52096CF979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE logaction_contact ADD CONSTRAINT FK_6FD79B88B306CBB FOREIGN KEY (log_action_id) REFERENCES log_action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE logaction_contact ADD CONSTRAINT FK_6FD79B8E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE log_action_business');
        $this->addSql('DROP TABLE log_action_company');
        $this->addSql('DROP TABLE log_action_contact');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE logaction_business DROP FOREIGN KEY FK_E52172878B306CBB');
        $this->addSql('ALTER TABLE logaction_company DROP FOREIGN KEY FK_52096CF8B306CBB');
        $this->addSql('ALTER TABLE logaction_contact DROP FOREIGN KEY FK_6FD79B88B306CBB');
        $this->addSql('CREATE TABLE log_action_business (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, business_id INT DEFAULT NULL, study_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, create_at DATETIME NOT NULL, INDEX IDX_5DEAB6AEA89DB457 (business_id), INDEX IDX_5DEAB6AEA76ED395 (user_id), INDEX IDX_5DEAB6AEE7B003E9 (study_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log_action_company (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, user_id INT DEFAULT NULL, study_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, create_at DATETIME NOT NULL, INDEX IDX_7341B0F8979B1AD6 (company_id), INDEX IDX_7341B0F8A76ED395 (user_id), INDEX IDX_7341B0F8E7B003E9 (study_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log_action_contact (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, contact_id INT DEFAULT NULL, study_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, create_at DATETIME NOT NULL, INDEX IDX_709C5F8FE7A1254A (contact_id), INDEX IDX_709C5F8FA76ED395 (user_id), INDEX IDX_709C5F8FE7B003E9 (study_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE log_action_business ADD CONSTRAINT FK_5DEAB6AEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE log_action_business ADD CONSTRAINT FK_5DEAB6AEA89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('ALTER TABLE log_action_business ADD CONSTRAINT FK_5DEAB6AEE7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
        $this->addSql('ALTER TABLE log_action_company ADD CONSTRAINT FK_7341B0F8979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE log_action_company ADD CONSTRAINT FK_7341B0F8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE log_action_company ADD CONSTRAINT FK_7341B0F8E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
        $this->addSql('ALTER TABLE log_action_contact ADD CONSTRAINT FK_709C5F8FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE log_action_contact ADD CONSTRAINT FK_709C5F8FE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE log_action_contact ADD CONSTRAINT FK_709C5F8FE7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
        $this->addSql('DROP TABLE log_action');
        $this->addSql('DROP TABLE logaction_business');
        $this->addSql('DROP TABLE logaction_company');
        $this->addSql('DROP TABLE logaction_contact');
    }
}
