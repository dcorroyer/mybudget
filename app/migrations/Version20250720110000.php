<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Create monthly_balance table for optimized balance history
 */
final class Version20250720110000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create monthly_balance table for optimized balance history aggregation';
    }

    public function up(Schema $schema): void
    {
        // Create monthly_balance table
        $this->addSql('CREATE TABLE monthly_balance (
            id SERIAL PRIMARY KEY,
            account_id INTEGER NOT NULL,
            month DATE NOT NULL,
            end_of_month_balance DOUBLE PRECISION NOT NULL DEFAULT 0,
            transaction_count INTEGER NOT NULL DEFAULT 0,
            last_updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            CONSTRAINT FK_monthly_balance_account FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE
        )');

        // Create unique constraint for account + month
        $this->addSql('CREATE UNIQUE INDEX unique_account_month ON monthly_balance (account_id, month)');
        
        // Create index on month for performance
        $this->addSql('CREATE INDEX IDX_monthly_balance_month ON monthly_balance (month)');
        
        // Create index on account for performance
        $this->addSql('CREATE INDEX IDX_monthly_balance_account ON monthly_balance (account_id)');

        // Add comments for documentation
        $this->addSql("COMMENT ON TABLE monthly_balance IS 'Optimized monthly balance aggregation to replace balance_history'");
        $this->addSql("COMMENT ON COLUMN monthly_balance.month IS 'First day of the month (YYYY-MM-01)'");
        $this->addSql("COMMENT ON COLUMN monthly_balance.end_of_month_balance IS 'Account balance at the end of this month'");
        $this->addSql("COMMENT ON COLUMN monthly_balance.transaction_count IS 'Number of transactions in this month'");
        $this->addSql("COMMENT ON COLUMN monthly_balance.last_updated IS 'When this monthly balance was last recalculated'");
    }

    public function down(Schema $schema): void
    {
        // Drop table and all indexes
        $this->addSql('DROP TABLE monthly_balance');
    }
}