<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180214174600 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE task_business (task_id INT NOT NULL, business_id INT NOT NULL, INDEX IDX_481384748DB60186 (task_id), INDEX IDX_48138474A89DB457 (business_id), PRIMARY KEY(task_id, business_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_company (task_id INT NOT NULL, company_id INT NOT NULL, INDEX IDX_505E23A58DB60186 (task_id), INDEX IDX_505E23A5979B1AD6 (company_id), PRIMARY KEY(task_id, company_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_contact (task_id INT NOT NULL, contact_id INT NOT NULL, INDEX IDX_5383CCD28DB60186 (task_id), INDEX IDX_5383CCD2E7A1254A (contact_id), PRIMARY KEY(task_id, contact_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_business ADD CONSTRAINT FK_481384748DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_business ADD CONSTRAINT FK_48138474A89DB457 FOREIGN KEY (business_id) REFERENCES business (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_company ADD CONSTRAINT FK_505E23A58DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_company ADD CONSTRAINT FK_505E23A5979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_contact ADD CONSTRAINT FK_5383CCD28DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_contact ADD CONSTRAINT FK_5383CCD2E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task ADD user_owner INT DEFAULT NULL, ADD study_id INT DEFAULT NULL, ADD user_assigned INT DEFAULT NULL, ADD create_at DATETIME NOT NULL, CHANGE parent_id parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB252DA21D24 FOREIGN KEY (user_owner) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25EB17289A FOREIGN KEY (user_assigned) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_527EDB252DA21D24 ON task (user_owner)');
        $this->addSql('CREATE INDEX IDX_527EDB25E7B003E9 ON task (study_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25EB17289A ON task (user_assigned)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE task_business');
        $this->addSql('DROP TABLE task_company');
        $this->addSql('DROP TABLE task_contact');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB252DA21D24');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25E7B003E9');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25EB17289A');
        $this->addSql('DROP INDEX IDX_527EDB252DA21D24 ON task');
        $this->addSql('DROP INDEX IDX_527EDB25E7B003E9 ON task');
        $this->addSql('DROP INDEX IDX_527EDB25EB17289A ON task');
        $this->addSql('ALTER TABLE task DROP user_owner, DROP study_id, DROP user_assigned, DROP create_at, CHANGE parent_id parent_id INT NOT NULL');
    }
}
