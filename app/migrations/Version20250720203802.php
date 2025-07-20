<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250720203802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove balance_history table - replaced by monthly_balance aggregation';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE balance_history_id_seq CASCADE');
        $this->addSql('ALTER TABLE balance_history DROP CONSTRAINT fk_135152f12fc0cb0f');
        $this->addSql('ALTER TABLE balance_history DROP CONSTRAINT fk_135152f19b6b5fba');
        $this->addSql('DROP TABLE balance_history');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE balance_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE balance_history (id INT NOT NULL, account_id INT NOT NULL, transaction_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, balance_before_transaction DOUBLE PRECISION NOT NULL, balance_after_transaction DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_135152f12fc0cb0f ON balance_history (transaction_id)');
        $this->addSql('CREATE INDEX idx_135152f19b6b5fba ON balance_history (account_id)');
        $this->addSql('ALTER TABLE balance_history ADD CONSTRAINT fk_135152f12fc0cb0f FOREIGN KEY (transaction_id) REFERENCES transaction (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE balance_history ADD CONSTRAINT fk_135152f19b6b5fba FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
