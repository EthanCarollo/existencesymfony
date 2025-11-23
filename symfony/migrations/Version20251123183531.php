<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251123183531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE buildings (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, model VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, length INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE character (id SERIAL NOT NULL, building_id INT NOT NULL, name VARCHAR(255) NOT NULL, personality TEXT NOT NULL, image VARCHAR(255) NOT NULL, personality_prompt TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_937AB0344D2A7E12 ON character (building_id)');
        $this->addSql('CREATE TABLE chat (id SERIAL NOT NULL, receiver_id INT NOT NULL, sended_id INT NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_659DF2AACD53EDB6 ON chat (receiver_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA8356BBDE ON chat (sended_id)');
        $this->addSql('COMMENT ON COLUMN chat.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE grid (id SERIAL NOT NULL, user_id INT NOT NULL, size INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2E20D937A76ED395 ON grid (user_id)');
        $this->addSql('CREATE TABLE grid_building (id SERIAL NOT NULL, building_id INT NOT NULL, grid_id INT NOT NULL, x_pos INT DEFAULT NULL, y_pos INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6EB6B9424D2A7E12 ON grid_building (building_id)');
        $this->addSql('CREATE INDEX IDX_6EB6B9422CF16895 ON grid_building (grid_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX unique_name ON "user" (name)');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB0344D2A7E12 FOREIGN KEY (building_id) REFERENCES grid_building (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA8356BBDE FOREIGN KEY (sended_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grid ADD CONSTRAINT FK_2E20D937A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grid_building ADD CONSTRAINT FK_6EB6B9424D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE grid_building ADD CONSTRAINT FK_6EB6B9422CF16895 FOREIGN KEY (grid_id) REFERENCES grid (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB0344D2A7E12');
        $this->addSql('ALTER TABLE chat DROP CONSTRAINT FK_659DF2AACD53EDB6');
        $this->addSql('ALTER TABLE chat DROP CONSTRAINT FK_659DF2AA8356BBDE');
        $this->addSql('ALTER TABLE grid DROP CONSTRAINT FK_2E20D937A76ED395');
        $this->addSql('ALTER TABLE grid_building DROP CONSTRAINT FK_6EB6B9424D2A7E12');
        $this->addSql('ALTER TABLE grid_building DROP CONSTRAINT FK_6EB6B9422CF16895');
        $this->addSql('DROP TABLE buildings');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE grid');
        $this->addSql('DROP TABLE grid_building');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
