<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240906190537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add date related fields';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE task ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE task ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP');

        $this->addSql('UPDATE task SET created_at = CURRENT_TIMESTAMP');
        $this->addSql('UPDATE task SET updated_at = CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE task DROP created_at');
        $this->addSql('ALTER TABLE task DROP updated_at');
    }
}
