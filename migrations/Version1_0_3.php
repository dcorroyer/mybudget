<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version1_0_3 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add timestamps to income and expense';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_lines RENAME INDEX idx_592580be55284914 TO IDX_EC092BACF395DB7B');
        $this->addSql('ALTER TABLE expense_lines RENAME INDEX idx_592580be12469de2 TO IDX_EC092BAC12469DE2');
        $this->addSql('ALTER TABLE expenses ADD created_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD updated_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE incomes ADD created_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD updated_at DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE expense_lines RENAME INDEX idx_ec092bacf395db7b TO IDX_592580BE55284914');
        $this->addSql('ALTER TABLE expense_lines RENAME INDEX idx_ec092bac12469de2 TO IDX_592580BE12469DE2');
        $this->addSql('ALTER TABLE incomes DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE expenses DROP created_at, DROP updated_at');
    }
}
