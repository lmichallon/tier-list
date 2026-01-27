<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260127183902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tier_list_items (id UUID NOT NULL, tier VARCHAR(10) NOT NULL, tier_list_id UUID NOT NULL, logo_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_929486BDB25FD8A1 ON tier_list_items (tier_list_id)');
        $this->addSql('CREATE INDEX IDX_929486BDF98F144A ON tier_list_items (logo_id)');
        $this->addSql('CREATE TABLE tier_lists (id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3CC8C72CA76ED395 ON tier_lists (user_id)');
        $this->addSql('ALTER TABLE tier_list_items ADD CONSTRAINT FK_929486BDB25FD8A1 FOREIGN KEY (tier_list_id) REFERENCES tier_lists (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('ALTER TABLE tier_list_items ADD CONSTRAINT FK_929486BDF98F144A FOREIGN KEY (logo_id) REFERENCES logos (id) NOT DEFERRABLE');
        $this->addSql('ALTER TABLE tier_lists ADD CONSTRAINT FK_3CC8C72CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('CREATE INDEX idx_refresh_token_hash ON refresh_tokens (token_hash)');
        $this->addSql('ALTER INDEX uniq_refresh_token_hash RENAME TO UNIQ_9BACE7E1B3BC57DA');
        $this->addSql('ALTER INDEX idx_refresh_token_user RENAME TO IDX_9BACE7E1A76ED395');
        $this->addSql('ALTER INDEX uniq_users_email RENAME TO UNIQ_1483A5E9E7927C74');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA pgbouncer');
        $this->addSql('CREATE SCHEMA realtime');
        $this->addSql('CREATE SCHEMA extensions');
        $this->addSql('CREATE SCHEMA vault');
        $this->addSql('CREATE SCHEMA graphql_public');
        $this->addSql('CREATE SCHEMA graphql');
        $this->addSql('CREATE SCHEMA auth');
        $this->addSql('CREATE SCHEMA storage');
        $this->addSql('ALTER TABLE tier_list_items DROP CONSTRAINT FK_929486BDB25FD8A1');
        $this->addSql('ALTER TABLE tier_list_items DROP CONSTRAINT FK_929486BDF98F144A');
        $this->addSql('ALTER TABLE tier_lists DROP CONSTRAINT FK_3CC8C72CA76ED395');
        $this->addSql('DROP TABLE tier_list_items');
        $this->addSql('DROP TABLE tier_lists');
        $this->addSql('DROP INDEX idx_refresh_token_hash');
        $this->addSql('ALTER INDEX idx_9bace7e1a76ed395 RENAME TO idx_refresh_token_user');
        $this->addSql('ALTER INDEX uniq_9bace7e1b3bc57da RENAME TO uniq_refresh_token_hash');
        $this->addSql('ALTER INDEX uniq_1483a5e9e7927c74 RENAME TO uniq_users_email');
    }
}
