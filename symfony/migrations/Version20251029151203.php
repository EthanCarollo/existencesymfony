<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251029151203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__character AS SELECT id, building_id, na�me, personality, image FROM character');
        $this->addSql('DROP TABLE character');
        $this->addSql('CREATE TABLE character (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, building_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, personality CLOB NOT NULL, image VARCHAR(255) NOT NULL, CONSTRAINT FK_937AB0344D2A7E12 FOREIGN KEY (building_id) REFERENCES grid_building (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO character (id, building_id, name, personality, image) SELECT id, building_id, na�me, personality, image FROM __temp__character');
        $this->addSql('DROP TABLE __temp__character');
        $this->addSql('CREATE INDEX IDX_937AB0344D2A7E12 ON character (building_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__character AS SELECT id, building_id, name, personality, image FROM character');
        $this->addSql('DROP TABLE character');
        $this->addSql('CREATE TABLE character (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, building_id INTEGER NOT NULL, na�me VARCHAR(255) NOT NULL, personality CLOB NOT NULL, image VARCHAR(255) NOT NULL, CONSTRAINT FK_937AB0344D2A7E12 FOREIGN KEY (building_id) REFERENCES grid_building (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO character (id, building_id, na�me, personality, image) SELECT id, building_id, name, personality, image FROM __temp__character');
        $this->addSql('DROP TABLE __temp__character');
        $this->addSql('CREATE INDEX IDX_937AB0344D2A7E12 ON character (building_id)');
    }
}
