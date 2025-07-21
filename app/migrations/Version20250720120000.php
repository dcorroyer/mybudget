<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migrate data from balance_history to monthly_balance
 */
final class Version20250720120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migrate existing balance_history data to monthly_balance aggregation';
    }

    public function up(Schema $schema): void
    {
        // Migrate data from balance_history to monthly_balance
        $this->addSql("
            INSERT INTO monthly_balance (account_id, month, end_of_month_balance, transaction_count, last_updated)
            WITH monthly_aggregates AS (
                SELECT 
                    account_id,
                    DATE_TRUNC('month', date)::date as month,
                    COUNT(*) as transaction_count,
                    MAX(date) as latest_date_in_month
                FROM balance_history
                GROUP BY account_id, DATE_TRUNC('month', date)
            ),
            monthly_balances AS (
                SELECT 
                    ma.account_id,
                    ma.month,
                    ma.transaction_count,
                    bh.balance_after_transaction as end_of_month_balance
                FROM monthly_aggregates ma
                JOIN balance_history bh ON (
                    bh.account_id = ma.account_id 
                    AND bh.date = ma.latest_date_in_month
                )
            )
            SELECT 
                account_id,
                month,
                end_of_month_balance,
                transaction_count,
                NOW() as last_updated
            FROM monthly_balances
            ORDER BY account_id, month
        ");

        // Verify migration integrity
        $this->addSql("
            DO $$
            DECLARE
                balance_history_months INTEGER;
                monthly_balance_months INTEGER;
            BEGIN
                SELECT COUNT(DISTINCT (account_id, DATE_TRUNC('month', date))) INTO balance_history_months FROM balance_history;
                SELECT COUNT(*) INTO monthly_balance_months FROM monthly_balance;
                
                IF balance_history_months != monthly_balance_months THEN
                    RAISE EXCEPTION 'Migration integrity check failed: % balance_history months vs % monthly_balance records', 
                        balance_history_months, monthly_balance_months;
                END IF;
                
                RAISE NOTICE 'Migration successful: % monthly balance records created', monthly_balance_months;
            END $$;
        ");
    }

    public function down(Schema $schema): void
    {
        // Clear monthly_balance data (balance_history should still exist)
        $this->addSql('DELETE FROM monthly_balance');
        
        $this->addSql("
            DO $$
            BEGIN
                RAISE NOTICE 'Reverted monthly_balance migration. Original balance_history data preserved.';
            END $$;
        ");
    }
}