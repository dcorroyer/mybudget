<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version1_0_5 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'move some fields from incomes to income_lines';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE income_lines (id INT AUTO_INCREMENT NOT NULL, income_id INT NOT NULL, name VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, created_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', updated_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_1B5EA2D8640ED2C0 (income_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE income_lines ADD CONSTRAINT FK_1B5EA2D8640ED2C0 FOREIGN KEY (income_id) REFERENCES incomes (id)');
        $this->addSql('ALTER TABLE expense_lines ADD created_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD updated_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE incomes DROP name, DROP type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE income_lines DROP FOREIGN KEY FK_1B5EA2D8640ED2C0');
        $this->addSql('DROP TABLE income_lines');
        $this->addSql('ALTER TABLE expense_lines DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE incomes ADD name VARCHAR(255) NOT NULL, ADD type VARCHAR(255) NOT NULL');
    }
}
