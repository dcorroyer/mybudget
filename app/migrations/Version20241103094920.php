<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241103094920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "account_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "balance_history_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "transaction_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "account" (id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7D3656A4A76ED395 ON "account" (user_id)');
        $this->addSql('CREATE TABLE "balance_history" (id INT NOT NULL, account_id INT NOT NULL, transaction_id INT NOT NULL, date DATE NOT NULL, balance DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_135152F19B6B5FBA ON "balance_history" (account_id)');
        $this->addSql('CREATE INDEX IDX_135152F12FC0CB0F ON "balance_history" (transaction_id)');
        $this->addSql('CREATE TABLE "transaction" (id INT NOT NULL, account_id INT NOT NULL, description VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D19B6B5FBA ON "transaction" (account_id)');
        $this->addSql('ALTER TABLE "account" ADD CONSTRAINT FK_7D3656A4A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "balance_history" ADD CONSTRAINT FK_135152F19B6B5FBA FOREIGN KEY (account_id) REFERENCES "account" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "balance_history" ADD CONSTRAINT FK_135152F12FC0CB0F FOREIGN KEY (transaction_id) REFERENCES "transaction" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "transaction" ADD CONSTRAINT FK_723705D19B6B5FBA FOREIGN KEY (account_id) REFERENCES "account" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "account_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "balance_history_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "transaction_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "account" DROP CONSTRAINT FK_7D3656A4A76ED395');
        $this->addSql('ALTER TABLE "balance_history" DROP CONSTRAINT FK_135152F19B6B5FBA');
        $this->addSql('ALTER TABLE "balance_history" DROP CONSTRAINT FK_135152F12FC0CB0F');
        $this->addSql('ALTER TABLE "transaction" DROP CONSTRAINT FK_723705D19B6B5FBA');
        $this->addSql('DROP TABLE "account"');
        $this->addSql('DROP TABLE "balance_history"');
        $this->addSql('DROP TABLE "transaction"');
    }
}
