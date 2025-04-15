<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250415194455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE clients (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, trainer_id INT DEFAULT NULL, age INT DEFAULT NULL, height INT DEFAULT NULL, weight INT DEFAULT NULL, fat_mass DOUBLE PRECISION DEFAULT NULL, free_fat_mass DOUBLE PRECISION DEFAULT NULL, total_body_water DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_C82E74A76ED395 (user_id), INDEX IDX_C82E74FB08EDF6 (trainer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE trainers (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, hourly_rate DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_20A5FACBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE clients ADD CONSTRAINT FK_C82E74A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE clients ADD CONSTRAINT FK_C82E74FB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainers (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trainers ADD CONSTRAINT FK_20A5FACBA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD phone VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_phone ON users (phone)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE clients DROP FOREIGN KEY FK_C82E74A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE clients DROP FOREIGN KEY FK_C82E74FB08EDF6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trainers DROP FOREIGN KEY FK_20A5FACBA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE clients
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE trainers
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_phone ON users
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP phone
        SQL);
    }
}
