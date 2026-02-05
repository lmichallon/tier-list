<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260205023000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add tier list payment metadata to users.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ADD tier_list_access_granted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD tier_list_access_amount_cents INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD tier_list_access_currency VARCHAR(8) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users DROP COLUMN tier_list_access_granted_at');
        $this->addSql('ALTER TABLE users DROP COLUMN tier_list_access_amount_cents');
        $this->addSql('ALTER TABLE users DROP COLUMN tier_list_access_currency');
    }
}
