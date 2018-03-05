<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180110180209 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD userextends_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499D11BAA9 FOREIGN KEY (userextends_id) REFERENCES user_extends (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6499D11BAA9 ON user (userextends_id)');
        $this->addSql('ALTER TABLE user_extends DROP FOREIGN KEY FK_71E004ABA76ED395');
        $this->addSql('DROP INDEX UNIQ_71E004ABA76ED395 ON user_extends');
        $this->addSql('ALTER TABLE user_extends DROP user_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499D11BAA9');
        $this->addSql('DROP INDEX UNIQ_8D93D6499D11BAA9 ON user');
        $this->addSql('ALTER TABLE user DROP userextends_id');
        $this->addSql('ALTER TABLE user_extends ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_extends ADD CONSTRAINT FK_71E004ABA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_71E004ABA76ED395 ON user_extends (user_id)');
    }
}
