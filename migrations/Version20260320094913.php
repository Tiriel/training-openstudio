<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260320094913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE matching (
              id BLOB NOT NULL,
              for_user_id INTEGER NOT NULL,
              conference_id BLOB NOT NULL,
              PRIMARY KEY (id),
              CONSTRAINT FK_DC10F2899B5BB4B8 FOREIGN KEY (for_user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE,
              CONSTRAINT FK_DC10F289604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_DC10F2899B5BB4B8 ON matching (for_user_id)');
        $this->addSql('CREATE INDEX IDX_DC10F289604B8382 ON matching (conference_id)');
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__conference_tag AS
            SELECT
              conference_id,
              tag_id
            FROM
              conference_tag
        SQL);
        $this->addSql('DROP TABLE conference_tag');
        $this->addSql(<<<'SQL'
            CREATE TABLE conference_tag (
              conference_id BLOB NOT NULL,
              tag_id INTEGER NOT NULL,
              PRIMARY KEY (conference_id, tag_id),
              CONSTRAINT FK_6B8B769F604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON
              UPDATE
                NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE,
                CONSTRAINT FK_6B8B769FBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON
              UPDATE
                NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO conference_tag (conference_id, tag_id)
            SELECT
              conference_id,
              tag_id
            FROM
              __temp__conference_tag
        SQL);
        $this->addSql('DROP TABLE __temp__conference_tag');
        $this->addSql('CREATE INDEX IDX_6B8B769FBAD26311 ON conference_tag (tag_id)');
        $this->addSql('CREATE INDEX IDX_6B8B769F604B8382 ON conference_tag (conference_id)');
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__conference_skill AS
            SELECT
              conference_id,
              skill_id
            FROM
              conference_skill
        SQL);
        $this->addSql('DROP TABLE conference_skill');
        $this->addSql(<<<'SQL'
            CREATE TABLE conference_skill (
              conference_id BLOB NOT NULL,
              skill_id INTEGER NOT NULL,
              PRIMARY KEY (conference_id, skill_id),
              CONSTRAINT FK_5429230E604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON
              UPDATE
                NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE,
                CONSTRAINT FK_5429230E5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON
              UPDATE
                NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO conference_skill (conference_id, skill_id)
            SELECT
              conference_id,
              skill_id
            FROM
              __temp__conference_skill
        SQL);
        $this->addSql('DROP TABLE __temp__conference_skill');
        $this->addSql('CREATE INDEX IDX_5429230E5585C142 ON conference_skill (skill_id)');
        $this->addSql('CREATE INDEX IDX_5429230E604B8382 ON conference_skill (conference_id)');
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
        $this->addSql('DROP TABLE matching');
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__conference_skill AS
            SELECT
              conference_id,
              skill_id
            FROM
              conference_skill
        SQL);
        $this->addSql('DROP TABLE conference_skill');
        $this->addSql(<<<'SQL'
            CREATE TABLE conference_skill (
              conference_id BLOB NOT NULL,
              skill_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              CONSTRAINT FK_5429230E604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE,
              CONSTRAINT FK_5429230E5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO conference_skill (conference_id, skill_id)
            SELECT
              conference_id,
              skill_id
            FROM
              __temp__conference_skill
        SQL);
        $this->addSql('DROP TABLE __temp__conference_skill');
        $this->addSql('CREATE INDEX IDX_5429230E604B8382 ON conference_skill (conference_id)');
        $this->addSql('CREATE INDEX IDX_5429230E5585C142 ON conference_skill (skill_id)');
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__conference_tag AS
            SELECT
              conference_id,
              tag_id
            FROM
              conference_tag
        SQL);
        $this->addSql('DROP TABLE conference_tag');
        $this->addSql(<<<'SQL'
            CREATE TABLE conference_tag (
              conference_id BLOB NOT NULL,
              tag_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              CONSTRAINT FK_6B8B769F604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE,
              CONSTRAINT FK_6B8B769FBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO conference_tag (conference_id, tag_id)
            SELECT
              conference_id,
              tag_id
            FROM
              __temp__conference_tag
        SQL);
        $this->addSql('DROP TABLE __temp__conference_tag');
        $this->addSql('CREATE INDEX IDX_6B8B769F604B8382 ON conference_tag (conference_id)');
        $this->addSql('CREATE INDEX IDX_6B8B769FBAD26311 ON conference_tag (tag_id)');
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
