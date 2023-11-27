<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version1_0_4 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create tracking table and drop data field in expenses and incomes';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tracking (id INT AUTO_INCREMENT NOT NULL, income_id INT NOT NULL, expense_id INT NOT NULL, name VARCHAR(255) NOT NULL, saving_capacity DOUBLE PRECISION NOT NULL, date DATE NOT NULL, created_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', updated_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', UNIQUE INDEX UNIQ_A87C621C640ED2C0 (income_id), UNIQUE INDEX UNIQ_A87C621CF395DB7B (expense_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tracking ADD CONSTRAINT FK_A87C621C640ED2C0 FOREIGN KEY (income_id) REFERENCES incomes (id)');
        $this->addSql('ALTER TABLE tracking ADD CONSTRAINT FK_A87C621CF395DB7B FOREIGN KEY (expense_id) REFERENCES expenses (id)');
        $this->addSql('ALTER TABLE expense_lines CHANGE expense_id expense_id INT NOT NULL, CHANGE category_id category_id INT NOT NULL');
        $this->addSql('ALTER TABLE expenses DROP date');
        $this->addSql('ALTER TABLE incomes DROP date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tracking DROP FOREIGN KEY FK_A87C621C640ED2C0');
        $this->addSql('ALTER TABLE tracking DROP FOREIGN KEY FK_A87C621CF395DB7B');
        $this->addSql('DROP TABLE tracking');
        $this->addSql('ALTER TABLE expense_lines CHANGE category_id category_id INT DEFAULT NULL, CHANGE expense_id expense_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE incomes ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE expenses ADD date DATE NOT NULL');
    }
}
