<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117074815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, departement_code VARCHAR(3) NOT NULL, insee_code VARCHAR(5) DEFAULT NULL, zip_code VARCHAR(5) DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, gps_lat DOUBLE PRECISION NOT NULL, gps_lng DOUBLE PRECISION NOT NULL, INDEX IDX_2D5B02346A333750 (departement_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE criteria (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (code VARCHAR(3) NOT NULL, region_code VARCHAR(3) NOT NULL, id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_C1765B63AEB327AF (region_code), INDEX code (code), PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lodging (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, number_rooms INT NOT NULL, max_people INT NOT NULL, surface DOUBLE PRECISION NOT NULL, weekly_base_price DOUBLE PRECISION NOT NULL, adress VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_8D35182A8BAC62AF (city_id), INDEX IDX_8D35182AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lodging_criteria (lodging_id INT NOT NULL, criteria_id INT NOT NULL, INDEX IDX_782B71B187335AF1 (lodging_id), INDEX IDX_782B71B1990BEA15 (criteria_id), PRIMARY KEY(lodging_id, criteria_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (code VARCHAR(3) NOT NULL, id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX code (code), PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, phone INT DEFAULT NULL, disponibility VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B02346A333750 FOREIGN KEY (departement_code) REFERENCES departement (code)');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B63AEB327AF FOREIGN KEY (region_code) REFERENCES region (code)');
        $this->addSql('ALTER TABLE lodging ADD CONSTRAINT FK_8D35182A8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE lodging ADD CONSTRAINT FK_8D35182AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE lodging_criteria ADD CONSTRAINT FK_782B71B187335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lodging_criteria ADD CONSTRAINT FK_782B71B1990BEA15 FOREIGN KEY (criteria_id) REFERENCES criteria (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B02346A333750');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B63AEB327AF');
        $this->addSql('ALTER TABLE lodging DROP FOREIGN KEY FK_8D35182A8BAC62AF');
        $this->addSql('ALTER TABLE lodging DROP FOREIGN KEY FK_8D35182AA76ED395');
        $this->addSql('ALTER TABLE lodging_criteria DROP FOREIGN KEY FK_782B71B187335AF1');
        $this->addSql('ALTER TABLE lodging_criteria DROP FOREIGN KEY FK_782B71B1990BEA15');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE criteria');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE lodging');
        $this->addSql('DROP TABLE lodging_criteria');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
