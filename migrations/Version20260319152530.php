<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260319152530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD COLUMN apikey VARCHAR(255) DEFAULT NULL');
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
        $this->addSql('CREATE INDEX IDX_41221F7EA76ED395 ON user_organization (user_id)');
        $this->addSql('CREATE INDEX IDX_41221F7E32C8A3DE ON user_organization (organization_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql(<<<'SQL'
            CREATE TABLE user (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              email VARCHAR(180) NOT NULL,
              roles CLOB NOT NULL,
              password VARCHAR(255) NOT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO user (id, email, roles, password)
            SELECT
              id,
              email,
              roles,
              password
            FROM
              __temp__user
        SQL);
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
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
