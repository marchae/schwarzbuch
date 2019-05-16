<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190320142111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'create event stream table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE event_stream (aggregate_root VARCHAR(255), aggregate_id CHAR(36), event VARCHAR(255), payload JSON);');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE event_stream;');
    }
}
