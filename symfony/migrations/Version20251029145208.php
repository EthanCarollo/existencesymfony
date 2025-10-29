<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251029145208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, building_id INTEGER NOT NULL, na�me VARCHAR(255) NOT NULL, personality CLOB NOT NULL, image VARCHAR(255) NOT NULL, CONSTRAINT FK_937AB0344D2A7E12 FOREIGN KEY (building_id) REFERENCES grid_building (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_937AB0344D2A7E12 ON character (building_id)');
        $this->addSql('CREATE TABLE chat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sender_id INTEGER NOT NULL, receiver_id INTEGER NOT NULL, sended_id INTEGER NOT NULL, message CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_659DF2AAF624B39D FOREIGN KEY (sender_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_659DF2AACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_659DF2AA8356BBDE FOREIGN KEY (sended_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_659DF2AAF624B39D ON chat (sender_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AACD53EDB6 ON chat (receiver_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA8356BBDE ON chat (sended_id)');
        $this->addSql('CREATE TABLE grid_building (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, building_id INTEGER NOT NULL, grid_id INTEGER NOT NULL, x_pos INTEGER DEFAULT NULL, y_pos INTEGER NOT NULL, CONSTRAINT FK_6EB6B9424D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6EB6B9422CF16895 FOREIGN KEY (grid_id) REFERENCES grid (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6EB6B9424D2A7E12 ON grid_building (building_id)');
        $this->addSql('CREATE INDEX IDX_6EB6B9422CF16895 ON grid_building (grid_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE grid_building');
    }
}
