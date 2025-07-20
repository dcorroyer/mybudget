<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Fix monthly_balance table to align with Doctrine entity expectations
 */
final class Version20250720130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix monthly_balance table schema to align with Doctrine entity expectations';
    }

    public function up(Schema $schema): void
    {
        // Drop old constraint and indexes first
        $this->addSql('ALTER TABLE monthly_balance DROP CONSTRAINT IF EXISTS FK_monthly_balance_account');
        $this->addSql('DROP INDEX IF EXISTS IDX_monthly_balance_month');
        $this->addSql('DROP INDEX IF EXISTS IDX_monthly_balance_account');
        $this->addSql('DROP INDEX IF EXISTS unique_account_month');

        // Alter column types and constraints to match Doctrine expectations
        $this->addSql('ALTER TABLE monthly_balance ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE monthly_balance ALTER month TYPE DATE');
        $this->addSql('ALTER TABLE monthly_balance ALTER end_of_month_balance DROP DEFAULT');
        $this->addSql('ALTER TABLE monthly_balance ALTER transaction_count DROP DEFAULT');
        $this->addSql('ALTER TABLE monthly_balance ALTER last_updated TYPE TIMESTAMP(0) WITHOUT TIME ZONE');

        // Add proper Doctrine comments for types
        $this->addSql("COMMENT ON COLUMN monthly_balance.month IS '(DC2Type:date_immutable)'");
        $this->addSql('COMMENT ON COLUMN monthly_balance.end_of_month_balance IS NULL');
        $this->addSql('COMMENT ON COLUMN monthly_balance.transaction_count IS NULL');
        $this->addSql("COMMENT ON COLUMN monthly_balance.last_updated IS '(DC2Type:datetime_immutable)'");

        // Add constraints and indexes with Doctrine-expected names
        $this->addSql('ALTER TABLE monthly_balance ADD CONSTRAINT FK_A0A1A4559B6B5FBA FOREIGN KEY (account_id) REFERENCES "account" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A0A1A4559B6B5FBA ON monthly_balance (account_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A0A1A4559B6B5FBA_MONTH ON monthly_balance (account_id, month)');
    }

    public function down(Schema $schema): void
    {
        // Revert to original schema (if needed)
        $this->addSql('ALTER TABLE monthly_balance DROP CONSTRAINT FK_A0A1A4559B6B5FBA');
        $this->addSql('DROP INDEX IDX_A0A1A4559B6B5FBA');
        $this->addSql('DROP INDEX UNIQ_A0A1A4559B6B5FBA_MONTH');
        
        // Restore original constraints
        $this->addSql('ALTER TABLE monthly_balance ADD CONSTRAINT FK_monthly_balance_account FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_monthly_balance_account ON monthly_balance (account_id)');
        $this->addSql('CREATE INDEX IDX_monthly_balance_month ON monthly_balance (month)');
        $this->addSql('CREATE UNIQUE INDEX unique_account_month ON monthly_balance (account_id, month)');
    }
}