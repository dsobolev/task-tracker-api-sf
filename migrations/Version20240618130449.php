<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240618130449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates table for tasks';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE task_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE task (
            id INT NOT NULL,
            title VARCHAR(50) NOT NULL,
            description TEXT DEFAULT NULL,
            PRIMARY KEY(id))
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE task_id_seq CASCADE');
        $this->addSql('DROP TABLE task');
    }
}
