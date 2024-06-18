<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240618131441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds status field to task table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE task ADD status SMALLINT NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE task DROP status');
    }
}
