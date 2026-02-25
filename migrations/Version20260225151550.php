<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225151550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activites (id SERIAL NOT NULL, calendrier_utilisateur_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_766B5EB56D8E27B0 ON activites (calendrier_utilisateur_id)');
        $this->addSql('COMMENT ON COLUMN activites.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN activites.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE calendriers (id SERIAL NOT NULL, type_calendriers_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, date_debut TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, date_fin TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A901B11FAD21A72F ON calendriers (type_calendriers_id)');
        $this->addSql('COMMENT ON COLUMN calendriers.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN calendriers.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE calendriers_utilisateurs (id SERIAL NOT NULL, utilisateur_id INT NOT NULL, calendrier_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FBDFCB48FB88E14F ON calendriers_utilisateurs (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_FBDFCB48FF52FC51 ON calendriers_utilisateurs (calendrier_id)');
        $this->addSql('COMMENT ON COLUMN calendriers_utilisateurs.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN calendriers_utilisateurs.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE effects_impacts (id SERIAL NOT NULL, activite_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, effect TEXT DEFAULT NULL, impact TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A7EC653C9B0F88B1 ON effects_impacts (activite_id)');
        $this->addSql('COMMENT ON COLUMN effects_impacts.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN effects_impacts.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE roles (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN roles.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN roles.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE type_calendriers (id SERIAL NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN type_calendriers.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN type_calendriers.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE utilisateurs (id SERIAL NOT NULL, role_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, email VARCHAR(255) NOT NULL, mdp VARCHAR(255) NOT NULL, entite VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_497B315ED60322AC ON utilisateurs (role_id)');
        $this->addSql('COMMENT ON COLUMN utilisateurs.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN utilisateurs.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE activites ADD CONSTRAINT FK_766B5EB56D8E27B0 FOREIGN KEY (calendrier_utilisateur_id) REFERENCES calendriers_utilisateurs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE calendriers ADD CONSTRAINT FK_A901B11FAD21A72F FOREIGN KEY (type_calendriers_id) REFERENCES type_calendriers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE calendriers_utilisateurs ADD CONSTRAINT FK_FBDFCB48FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE calendriers_utilisateurs ADD CONSTRAINT FK_FBDFCB48FF52FC51 FOREIGN KEY (calendrier_id) REFERENCES calendriers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE effects_impacts ADD CONSTRAINT FK_A7EC653C9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activites (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisateurs ADD CONSTRAINT FK_497B315ED60322AC FOREIGN KEY (role_id) REFERENCES roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE activites DROP CONSTRAINT FK_766B5EB56D8E27B0');
        $this->addSql('ALTER TABLE calendriers DROP CONSTRAINT FK_A901B11FAD21A72F');
        $this->addSql('ALTER TABLE calendriers_utilisateurs DROP CONSTRAINT FK_FBDFCB48FB88E14F');
        $this->addSql('ALTER TABLE calendriers_utilisateurs DROP CONSTRAINT FK_FBDFCB48FF52FC51');
        $this->addSql('ALTER TABLE effects_impacts DROP CONSTRAINT FK_A7EC653C9B0F88B1');
        $this->addSql('ALTER TABLE utilisateurs DROP CONSTRAINT FK_497B315ED60322AC');
        $this->addSql('DROP TABLE activites');
        $this->addSql('DROP TABLE calendriers');
        $this->addSql('DROP TABLE calendriers_utilisateurs');
        $this->addSql('DROP TABLE effects_impacts');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE type_calendriers');
        $this->addSql('DROP TABLE utilisateurs');
    }
}
