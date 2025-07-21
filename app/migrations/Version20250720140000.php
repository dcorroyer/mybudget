<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Fix index name to match entity annotation
 */
final class Version20250720140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename unique index to match entity annotation name';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER INDEX uniq_a0a1a4559b6b5fba_month RENAME TO unique_account_month');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER INDEX unique_account_month RENAME TO uniq_a0a1a4559b6b5fba_month');
    }
}