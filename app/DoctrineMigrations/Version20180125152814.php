<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180125152814 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE log_action_contact ADD contact_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD study_id INT DEFAULT NULL, DROP contact, DROP userOwner, DROP study');
        $this->addSql('ALTER TABLE log_action_contact ADD CONSTRAINT FK_709C5F8FE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id)');
        $this->addSql('ALTER TABLE log_action_contact ADD CONSTRAINT FK_709C5F8FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE log_action_contact ADD CONSTRAINT FK_709C5F8FE7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
        $this->addSql('CREATE INDEX IDX_709C5F8FE7A1254A ON log_action_contact (contact_id)');
        $this->addSql('CREATE INDEX IDX_709C5F8FA76ED395 ON log_action_contact (user_id)');
        $this->addSql('CREATE INDEX IDX_709C5F8FE7B003E9 ON log_action_contact (study_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE log_action_contact DROP FOREIGN KEY FK_709C5F8FE7A1254A');
        $this->addSql('ALTER TABLE log_action_contact DROP FOREIGN KEY FK_709C5F8FA76ED395');
        $this->addSql('ALTER TABLE log_action_contact DROP FOREIGN KEY FK_709C5F8FE7B003E9');
        $this->addSql('DROP INDEX IDX_709C5F8FE7A1254A ON log_action_contact');
        $this->addSql('DROP INDEX IDX_709C5F8FA76ED395 ON log_action_contact');
        $this->addSql('DROP INDEX IDX_709C5F8FE7B003E9 ON log_action_contact');
        $this->addSql('ALTER TABLE log_action_contact ADD contact VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD userOwner VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD study VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP contact_id, DROP user_id, DROP study_id');
    }
}
