<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160304182218 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event CHANGE start_time start_time DATETIME NOT NULL, CHANGE end_time end_time DATETIME NOT NULL');
        $this->addSql('ALTER TABLE evaluation ADD dateCreated DATETIME NOT NULL, ADD dateCompleted DATETIME DEFAULT NULL, ADD status enum(\'create\', \'pending\', \'ongoing\', \'done\', \'cancelled\')');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE evaluation DROP dateCreated, DROP dateCompleted, DROP status');
        $this->addSql('ALTER TABLE event CHANGE start_time start_time DATE NOT NULL, CHANGE end_time end_time DATE NOT NULL');
    }
}
