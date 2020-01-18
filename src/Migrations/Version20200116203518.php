<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200116203518 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE "right" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, label VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE role (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, role_key VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE role_right (role_id INTEGER NOT NULL, right_id INTEGER NOT NULL, PRIMARY KEY(role_id, right_id))');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, role_id INTEGER NOT NULL)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE "right"');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_right');
        $this->addSql('DROP TABLE user');
    }
}
