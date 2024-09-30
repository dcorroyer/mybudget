<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240928203551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "budget_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "expense_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "income_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "budget" (id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, incomes_amount DOUBLE PRECISION NOT NULL, expenses_amount DOUBLE PRECISION NOT NULL, saving_capacity DOUBLE PRECISION NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_73F2F77BAA9E377A ON "budget" (date)');
        $this->addSql('CREATE INDEX IDX_73F2F77BA76ED395 ON "budget" (user_id)');
        $this->addSql('CREATE TABLE "expense" (id INT NOT NULL, budget_id INT NOT NULL, name VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, category VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D3A8DA636ABA6B8 ON "expense" (budget_id)');
        $this->addSql('CREATE TABLE "income" (id INT NOT NULL, budget_id INT NOT NULL, name VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3FA862D036ABA6B8 ON "income" (budget_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, first_name VARCHAR(180) NOT NULL, last_name VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(180) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('ALTER TABLE "budget" ADD CONSTRAINT FK_73F2F77BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "expense" ADD CONSTRAINT FK_2D3A8DA636ABA6B8 FOREIGN KEY (budget_id) REFERENCES "budget" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "income" ADD CONSTRAINT FK_3FA862D036ABA6B8 FOREIGN KEY (budget_id) REFERENCES "budget" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "budget_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "expense_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "income_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "budget" DROP CONSTRAINT FK_73F2F77BA76ED395');
        $this->addSql('ALTER TABLE "expense" DROP CONSTRAINT FK_2D3A8DA636ABA6B8');
        $this->addSql('ALTER TABLE "income" DROP CONSTRAINT FK_3FA862D036ABA6B8');
        $this->addSql('DROP TABLE "budget"');
        $this->addSql('DROP TABLE "expense"');
        $this->addSql('DROP TABLE "income"');
        $this->addSql('DROP TABLE "user"');
    }
}
