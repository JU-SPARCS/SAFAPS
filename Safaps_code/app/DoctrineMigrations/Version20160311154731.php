<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160311154731 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5757A7B643');
        $this->addSql('ALTER TABLE evaluation CHANGE status status enum(\'create\', \'pending\', \'ongoing\', \'done\', \'cancelled\')');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5757A7B643 FOREIGN KEY (result_id) REFERENCES result (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5757A7B643');
        $this->addSql('ALTER TABLE evaluation CHANGE status status VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5757A7B643 FOREIGN KEY (result_id) REFERENCES organization (id)');
    }
}
