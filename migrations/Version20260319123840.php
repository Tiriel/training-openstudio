<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260319123840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__conference AS
            SELECT
              id,
              name,
              description,
              accessible,
              prerequisites,
              start_at,
              end_at
            FROM
              conference
        SQL);
        $this->addSql('DROP TABLE conference');
        $this->addSql(<<<'SQL'
            CREATE TABLE conference (
              id BLOB NOT NULL,
              name VARCHAR(255) NOT NULL,
              description CLOB NOT NULL,
              accessible BOOLEAN NOT NULL,
              prerequisites CLOB DEFAULT NULL,
              start_at DATETIME NOT NULL,
              end_at DATETIME NOT NULL,
              created_by_id INTEGER NOT NULL,
              PRIMARY KEY (id),
              CONSTRAINT FK_911533C8B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO conference (
              id, name, description, accessible,
              prerequisites, start_at, end_at
            )
            SELECT
              id,
              name,
              description,
              accessible,
              prerequisites,
              start_at,
              end_at
            FROM
              __temp__conference
        SQL);
        $this->addSql('DROP TABLE __temp__conference');
        $this->addSql('CREATE INDEX IDX_911533C8B03A8386 ON conference (created_by_id)');
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__user_organization AS
            SELECT
              user_id,
              organization_id
            FROM
              user_organization
        SQL);
        $this->addSql('DROP TABLE user_organization');
        $this->addSql(<<<'SQL'
            CREATE TABLE user_organization (
              user_id INTEGER NOT NULL,
              organization_id BLOB NOT NULL,
              PRIMARY KEY (user_id, organization_id),
              CONSTRAINT FK_41221F7EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON
              UPDATE
                NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE,
                CONSTRAINT FK_41221F7E32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON
              UPDATE
                NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO user_organization (user_id, organization_id)
            SELECT
              user_id,
              organization_id
            FROM
              __temp__user_organization
        SQL);
        $this->addSql('DROP TABLE __temp__user_organization');
        $this->addSql('CREATE INDEX IDX_41221F7E32C8A3DE ON user_organization (organization_id)');
        $this->addSql('CREATE INDEX IDX_41221F7EA76ED395 ON user_organization (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__conference AS
            SELECT
              id,
              name,
              description,
              accessible,
              prerequisites,
              start_at,
              end_at
            FROM
              conference
        SQL);
        $this->addSql('DROP TABLE conference');
        $this->addSql(<<<'SQL'
            CREATE TABLE conference (
              id BLOB NOT NULL,
              name VARCHAR(255) NOT NULL,
              description CLOB NOT NULL,
              accessible BOOLEAN NOT NULL,
              prerequisites CLOB DEFAULT NULL,
              start_at DATETIME NOT NULL,
              end_at DATETIME NOT NULL,
              PRIMARY KEY (id)
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO conference (
              id, name, description, accessible,
              prerequisites, start_at, end_at
            )
            SELECT
              id,
              name,
              description,
              accessible,
              prerequisites,
              start_at,
              end_at
            FROM
              __temp__conference
        SQL);
        $this->addSql('DROP TABLE __temp__conference');
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__user_organization AS
            SELECT
              user_id,
              organization_id
            FROM
              user_organization
        SQL);
        $this->addSql('DROP TABLE user_organization');
        $this->addSql(<<<'SQL'
            CREATE TABLE user_organization (
              user_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              organization_id BLOB NOT NULL,
              CONSTRAINT FK_41221F7EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE,
              CONSTRAINT FK_41221F7E32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO user_organization (user_id, organization_id)
            SELECT
              user_id,
              organization_id
            FROM
              __temp__user_organization
        SQL);
        $this->addSql('DROP TABLE __temp__user_organization');
        $this->addSql('CREATE INDEX IDX_41221F7EA76ED395 ON user_organization (user_id)');
        $this->addSql('CREATE INDEX IDX_41221F7E32C8A3DE ON user_organization (organization_id)');
    }
}
