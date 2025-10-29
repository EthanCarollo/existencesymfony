<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251029091946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE buildings ADD COLUMN image VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__buildings AS SELECT id, name, width, height, model FROM buildings');
        $this->addSql('DROP TABLE buildings');
        $this->addSql('CREATE TABLE buildings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, width INTEGER NOT NULL, height INTEGER NOT NULL, model VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO buildings (id, name, width, height, model) SELECT id, name, width, height, model FROM __temp__buildings');
        $this->addSql('DROP TABLE __temp__buildings');
    }
}
