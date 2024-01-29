<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version1_0_6 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add relation between users and trackings';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE income_lines RENAME INDEX idx_1b5ea2d8640ed2c0 TO IDX_931DCB4F640ED2C0');
        $this->addSql('ALTER TABLE trackings ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE trackings ADD CONSTRAINT FK_FA7EF267E3C61F9 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_FA7EF267E3C61F9 ON trackings (user_id)');
        $this->addSql('ALTER TABLE trackings RENAME INDEX uniq_a87c621c640ed2c0 TO UNIQ_FA7EF26640ED2C0');
        $this->addSql('ALTER TABLE trackings RENAME INDEX uniq_a87c621cf395db7b TO UNIQ_FA7EF26F395DB7B');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trackings DROP FOREIGN KEY FK_FA7EF267E3C61F9');
        $this->addSql('DROP INDEX IDX_FA7EF267E3C61F9 ON trackings');
        $this->addSql('ALTER TABLE trackings DROP user_id');
        $this->addSql('ALTER TABLE trackings RENAME INDEX uniq_fa7ef26640ed2c0 TO UNIQ_A87C621C640ED2C0');
        $this->addSql('ALTER TABLE trackings RENAME INDEX uniq_fa7ef26f395db7b TO UNIQ_A87C621CF395DB7B');
        $this->addSql('ALTER TABLE income_lines RENAME INDEX idx_931dcb4f640ed2c0 TO IDX_1B5EA2D8640ED2C0');
    }
}
