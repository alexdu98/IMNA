<?php

declare(strict_types=1);

namespace AppBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version2_0_0_P2 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Doit être joué après l\'exécution de la commande de Canonicalisation';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE membre ADD CONSTRAINT uc_mbre_usernamcan UNIQUE (username_canonical)');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT uc_mbre_emailcan UNIQUE (email_canonical)');

        $this->addSql('CREATE TABLE sessions (sess_id VARCHAR(128) NOT NULL PRIMARY KEY, sess_data BLOB NOT NULL, sess_time INTEGER UNSIGNED NOT NULL, sess_lifetime INTEGER UNSIGNED NOT NULL) COLLATE utf8mb4_bin, ENGINE = InnoDB;');
    }

    public function down(Schema $schema) : void
    {

    }
}
