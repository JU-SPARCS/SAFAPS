<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160305185041 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE invoice ADD period_end DATETIME NOT NULL, CHANGE creation_date creation_date DATETIME NOT NULL, CHANGE period_start period_start DATETIME NOT NULL');
        $this->addSql('ALTER TABLE evaluation CHANGE status status enum(\'create\', \'pending\', \'ongoing\', \'done\', \'cancelled\')');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE evaluation CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE invoice DROP period_end, CHANGE creation_date creation_date DATE NOT NULL, CHANGE period_start period_start DATE NOT NULL');
    }
}
