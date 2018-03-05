<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180108171055 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_extends (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, study_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_71E004ABA76ED395 (user_id), UNIQUE INDEX UNIQ_71E004ABE7B003E9 (study_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE study (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_E67F97493A909126 (nombre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_extends ADD CONSTRAINT FK_71E004ABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_extends ADD CONSTRAINT FK_71E004ABE7B003E9 FOREIGN KEY (study_id) REFERENCES study (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_extends DROP FOREIGN KEY FK_71E004ABE7B003E9');
        $this->addSql('DROP TABLE user_extends');
        $this->addSql('DROP TABLE study');
    }
}
