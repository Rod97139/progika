<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230112110623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE criteria (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lodging_criteria (lodging_id INT NOT NULL, criteria_id INT NOT NULL, INDEX IDX_782B71B187335AF1 (lodging_id), INDEX IDX_782B71B1990BEA15 (criteria_id), PRIMARY KEY(lodging_id, criteria_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, phone INT DEFAULT NULL, disponibility VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lodging_criteria ADD CONSTRAINT FK_782B71B187335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lodging_criteria ADD CONSTRAINT FK_782B71B1990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lodging ADD city_id INT NOT NULL, ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE lodging ADD CONSTRAINT FK_8D35182A8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE lodging ADD CONSTRAINT FK_8D35182AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8D35182A8BAC62AF ON lodging (city_id)');
        $this->addSql('CREATE INDEX IDX_8D35182AA76ED395 ON lodging (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lodging DROP FOREIGN KEY FK_8D35182AA76ED395');
        $this->addSql('ALTER TABLE lodging_criteria DROP FOREIGN KEY FK_782B71B187335AF1');
        $this->addSql('ALTER TABLE lodging_criteria DROP FOREIGN KEY FK_782B71B1990BEA15');
        $this->addSql('DROP TABLE criteria');
        $this->addSql('DROP TABLE lodging_criteria');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE lodging DROP FOREIGN KEY FK_8D35182A8BAC62AF');
        $this->addSql('DROP INDEX IDX_8D35182A8BAC62AF ON lodging');
        $this->addSql('DROP INDEX IDX_8D35182AA76ED395 ON lodging');
        $this->addSql('ALTER TABLE lodging DROP city_id, DROP user_id');
    }
}
