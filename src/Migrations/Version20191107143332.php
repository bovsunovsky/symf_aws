<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191107143332 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE client_adress DROP FOREIGN KEY fk-client-adress');
        $this->addSql('CREATE TABLE notes (id INT AUTO_INCREMENT NOT NULL, 
                                                title VARCHAR(255) NOT NULL, 
                                                description VARCHAR(1200) DEFAULT NULL, 
                                                status SMALLINT DEFAULT NULL, 
                                                created DATETIME DEFAULT NULL, 
                                                PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE client_adress');
        $this->addSql('DROP TABLE migration');
        $this->addSql('DROP TABLE user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, pass VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, firstname VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, lastname VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, sex TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, email VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, UNIQUE INDEX login (login), UNIQUE INDEX email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE client_adress (id INT AUTO_INCREMENT NOT NULL, parent_id INT NOT NULL, postcode VARCHAR(32) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, country VARCHAR(4) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, sity VARCHAR(128) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, street VARCHAR(128) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, building INT DEFAULT NULL, office INT DEFAULT NULL, INDEX fk-client-adress (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE migration (version VARCHAR(180) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, apply_time INT DEFAULT NULL, PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, auth_key VARCHAR(32) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, password_hash VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, password_reset_token VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, status SMALLINT DEFAULT 10 NOT NULL, created_at INT NOT NULL, updated_at INT NOT NULL, verification_token VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, UNIQUE INDEX username (username), UNIQUE INDEX password_reset_token (password_reset_token), UNIQUE INDEX email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE client_adress ADD CONSTRAINT fk-client-adress FOREIGN KEY (parent_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE notes');
    }
}
