<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180219150754 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE task_state (id INT AUTO_INCREMENT NOT NULL, isTerminal TINYINT(1) NOT NULL, isInitial TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task ADD state INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25A393D2FB FOREIGN KEY (state) REFERENCES task_state (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25A393D2FB ON task (state)');
        $this->addSql('INSERT INTO task_state (name, isTerminal, isInitial) VALUES ("Inicio", 0, 1)');
        $this->addSql('INSERT INTO task_state (name, isTerminal, isInitial) VALUES ("Ejecución", 0, 0)');
        $this->addSql('INSERT INTO task_state (name, isTerminal, isInitial) VALUES ("Fin", 1, 0)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25A393D2FB');
        $this->addSql('DROP TABLE task_state');
        $this->addSql('DROP INDEX IDX_527EDB25A393D2FB ON task');
        $this->addSql('ALTER TABLE task DROP state');
    }
}
