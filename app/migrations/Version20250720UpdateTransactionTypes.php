<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250720UpdateTransactionTypes extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update transaction types from DEBIT/CREDIT to WITHDRAWAL/DEPOSIT';
    }

    public function up(Schema $schema): void
    {
        // Update existing transaction types
        $this->addSql("UPDATE transaction SET type = 'WITHDRAWAL' WHERE type = 'DEBIT'");
        $this->addSql("UPDATE transaction SET type = 'DEPOSIT' WHERE type = 'CREDIT'");
    }

    public function down(Schema $schema): void
    {
        // Revert transaction types back
        $this->addSql("UPDATE transaction SET type = 'DEBIT' WHERE type = 'WITHDRAWAL'");
        $this->addSql("UPDATE transaction SET type = 'CREDIT' WHERE type = 'DEPOSIT'");
    }
}