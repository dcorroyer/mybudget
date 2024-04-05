<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240405131012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE budgets (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, saving_capacity DOUBLE PRECISION NOT NULL, date DATE NOT NULL, INDEX IDX_DCAA9548A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expenses (id INT AUTO_INCREMENT NOT NULL, expense_category_id INT NOT NULL, budget_id INT NOT NULL, name VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_2496F35B6B2A3179 (expense_category_id), INDEX IDX_2496F35B36ABA6B8 (budget_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expenses_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE incomes (id INT AUTO_INCREMENT NOT NULL, budget_id INT NOT NULL, name VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_9DE2B5BD36ABA6B8 (budget_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, first_name VARCHAR(180) NOT NULL, last_name VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE budgets ADD CONSTRAINT FK_DCAA9548A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE expenses ADD CONSTRAINT FK_2496F35B6B2A3179 FOREIGN KEY (expense_category_id) REFERENCES expenses_categories (id)');
        $this->addSql('ALTER TABLE expenses ADD CONSTRAINT FK_2496F35B36ABA6B8 FOREIGN KEY (budget_id) REFERENCES budgets (id)');
        $this->addSql('ALTER TABLE incomes ADD CONSTRAINT FK_9DE2B5BD36ABA6B8 FOREIGN KEY (budget_id) REFERENCES budgets (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE budgets DROP FOREIGN KEY FK_DCAA9548A76ED395');
        $this->addSql('ALTER TABLE expenses DROP FOREIGN KEY FK_2496F35B6B2A3179');
        $this->addSql('ALTER TABLE expenses DROP FOREIGN KEY FK_2496F35B36ABA6B8');
        $this->addSql('ALTER TABLE incomes DROP FOREIGN KEY FK_9DE2B5BD36ABA6B8');
        $this->addSql('DROP TABLE budgets');
        $this->addSql('DROP TABLE expenses');
        $this->addSql('DROP TABLE expenses_categories');
        $this->addSql('DROP TABLE incomes');
        $this->addSql('DROP TABLE users');
    }
}
