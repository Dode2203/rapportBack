<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260227020427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_effect_impacts (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN type_effect_impacts.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN type_effect_impacts.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE effects_impacts ADD type_effect_impact_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE effects_impacts ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE effects_impacts DROP effect');
        $this->addSql('ALTER TABLE effects_impacts DROP impact');
        $this->addSql('ALTER TABLE effects_impacts ADD CONSTRAINT FK_A7EC653C91324CCF FOREIGN KEY (type_effect_impact_id) REFERENCES type_effect_impacts (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A7EC653C91324CCF ON effects_impacts (type_effect_impact_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE effects_impacts DROP CONSTRAINT FK_A7EC653C91324CCF');
        $this->addSql('DROP TABLE type_effect_impacts');
        $this->addSql('DROP INDEX IDX_A7EC653C91324CCF');
        $this->addSql('ALTER TABLE effects_impacts ADD effect TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE effects_impacts ADD impact TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE effects_impacts DROP type_effect_impact_id');
        $this->addSql('ALTER TABLE effects_impacts DROP name');
    }
}
