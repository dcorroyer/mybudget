<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version1_0_2 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE categories (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                PRIMARY KEY(id))
                DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('
            CREATE TABLE charge_lines (
                id INT AUTO_INCREMENT NOT NULL,
                charge_id INT DEFAULT NULL,
                category_id INT DEFAULT NULL,
                name VARCHAR(255) NOT NULL,
                amount DOUBLE PRECISION NOT NULL,
                INDEX IDX_592580BE55284914 (charge_id),
                INDEX IDX_592580BE12469DE2 (category_id),
                PRIMARY KEY(id))
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('
            CREATE TABLE charges (
                id INT AUTO_INCREMENT NOT NULL,
                amount DOUBLE PRECISION NOT NULL,
                date DATE NOT NULL, PRIMARY KEY(id))
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('
            ALTER TABLE charge_lines
                ADD CONSTRAINT FK_592580BE55284914
                    FOREIGN KEY (charge_id) REFERENCES charges (id)');
        $this->addSql('
            ALTER TABLE charge_lines
                ADD CONSTRAINT FK_592580BE12469DE2
                    FOREIGN KEY (category_id) REFERENCES categories (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE charge_lines DROP FOREIGN KEY FK_592580BE55284914');
        $this->addSql('ALTER TABLE charge_lines DROP FOREIGN KEY FK_592580BE12469DE2');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE charge_lines');
        $this->addSql('DROP TABLE charges');
    }
}
