<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180216153913 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recurrence ADD user_id INT DEFAULT NULL, ADD study_id INT DEFAULT NULL, CHANGE dayOfMonth dayOfMonth INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recurrence ADD CONSTRAINT FK_1FB7F221A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recurrence ADD CONSTRAINT FK_1FB7F221E7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
        $this->addSql('CREATE INDEX IDX_1FB7F221A76ED395 ON recurrence (user_id)');
        $this->addSql('CREATE INDEX IDX_1FB7F221E7B003E9 ON recurrence (study_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recurrence DROP FOREIGN KEY FK_1FB7F221A76ED395');
        $this->addSql('ALTER TABLE recurrence DROP FOREIGN KEY FK_1FB7F221E7B003E9');
        $this->addSql('DROP INDEX IDX_1FB7F221A76ED395 ON recurrence');
        $this->addSql('DROP INDEX IDX_1FB7F221E7B003E9 ON recurrence');
        $this->addSql('ALTER TABLE recurrence DROP user_id, DROP study_id, CHANGE dayOfMonth dayOfMonth INT NOT NULL');
    }
}
