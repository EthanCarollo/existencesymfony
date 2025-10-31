<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251031105521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__chat AS SELECT id, receiver_id, sended_id, message, created_at FROM chat');
        $this->addSql('DROP TABLE chat');
        $this->addSql('CREATE TABLE chat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, receiver_id INTEGER NOT NULL, sended_id INTEGER NOT NULL, message CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_659DF2AACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES character (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_659DF2AA8356BBDE FOREIGN KEY (sended_id) REFERENCES character (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO chat (id, receiver_id, sended_id, message, created_at) SELECT id, receiver_id, sended_id, message, created_at FROM __temp__chat');
        $this->addSql('DROP TABLE __temp__chat');
        $this->addSql('CREATE INDEX IDX_659DF2AA8356BBDE ON chat (sended_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AACD53EDB6 ON chat (receiver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__chat AS SELECT id, receiver_id, sended_id, message, created_at FROM chat');
        $this->addSql('DROP TABLE chat');
        $this->addSql('CREATE TABLE chat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, receiver_id INTEGER NOT NULL, sended_id INTEGER NOT NULL, sender_id INTEGER NOT NULL, message CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_659DF2AACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_659DF2AA8356BBDE FOREIGN KEY (sended_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_659DF2AAF624B39D FOREIGN KEY (sender_id) REFERENCES character (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO chat (id, receiver_id, sended_id, message, created_at) SELECT id, receiver_id, sended_id, message, created_at FROM __temp__chat');
        $this->addSql('DROP TABLE __temp__chat');
        $this->addSql('CREATE INDEX IDX_659DF2AACD53EDB6 ON chat (receiver_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA8356BBDE ON chat (sended_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AAF624B39D ON chat (sender_id)');
    }
}
