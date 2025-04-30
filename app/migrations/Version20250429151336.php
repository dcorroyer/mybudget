<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250429151336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add payment_method to expense table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE expense ADD payment_method VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE expense SET payment_method = 'OTHER' WHERE payment_method IS NULL");
        $this->addSql('ALTER TABLE expense ALTER COLUMN payment_method SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "expense" DROP payment_method');
    }
}
