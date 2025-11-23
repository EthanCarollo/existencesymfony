<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251123195250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE buildings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, width INTEGER NOT NULL, height INTEGER NOT NULL, model VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, length INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE character (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, building_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, personality CLOB NOT NULL, image VARCHAR(255) NOT NULL, personality_prompt CLOB NOT NULL, CONSTRAINT FK_937AB0344D2A7E12 FOREIGN KEY (building_id) REFERENCES grid_building (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_937AB0344D2A7E12 ON character (building_id)');
        $this->addSql('CREATE TABLE chat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, receiver_id INTEGER NOT NULL, sended_id INTEGER NOT NULL, message CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_659DF2AACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_659DF2AA8356BBDE FOREIGN KEY (sended_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_659DF2AACD53EDB6 ON chat (receiver_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA8356BBDE ON chat (sended_id)');
        $this->addSql('CREATE TABLE grid (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, size INTEGER NOT NULL, CONSTRAINT FK_2E20D937A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2E20D937A76ED395 ON grid (user_id)');
        $this->addSql('CREATE TABLE grid_building (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, building_id INTEGER NOT NULL, grid_id INTEGER NOT NULL, x_pos INTEGER DEFAULT NULL, y_pos INTEGER NOT NULL, CONSTRAINT FK_6EB6B9424D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6EB6B9422CF16895 FOREIGN KEY (grid_id) REFERENCES grid (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6EB6B9424D2A7E12 ON grid_building (building_id)');
        $this->addSql('CREATE INDEX IDX_6EB6B9422CF16895 ON grid_building (grid_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX unique_name ON user (name)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE buildings');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE grid');
        $this->addSql('DROP TABLE grid_building');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
