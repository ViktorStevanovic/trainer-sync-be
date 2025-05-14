<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250514200432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_active ON availability_overrides (active)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_full_day_override ON availability_overrides (full_day_override)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_date ON availability_overrides (date)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_start_time ON availability_overrides (start_time)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_end_time ON availability_overrides (end_time)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_date_start_end_time ON availability_overrides (date, start_time, end_time)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_active ON availability_slots (active)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_booked ON availability_slots (booked)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_date ON availability_slots (date)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_start_time ON availability_slots (start_time)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_end_time ON availability_slots (end_time)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_date_start_end_time ON availability_slots (date, start_time, end_time)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_week_day ON schedule_templates (week_day)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX idx_active ON availability_overrides
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_full_day_override ON availability_overrides
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_date ON availability_overrides
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_start_time ON availability_overrides
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_end_time ON availability_overrides
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_date_start_end_time ON availability_overrides
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_week_day ON schedule_templates
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_active ON availability_slots
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_booked ON availability_slots
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_date ON availability_slots
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_start_time ON availability_slots
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_end_time ON availability_slots
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_date_start_end_time ON availability_slots
        SQL);
    }
}
