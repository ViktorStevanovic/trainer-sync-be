<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250503101830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE appointments (id INT AUTO_INCREMENT NOT NULL, trainer_id INT DEFAULT NULL, client_id INT DEFAULT NULL, availability_slot_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_6A41727AFB08EDF6 (trainer_id), INDEX IDX_6A41727A19EB6921 (client_id), UNIQUE INDEX UNIQ_6A41727AD6F1FA37 (availability_slot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE availability_overrides (id INT AUTO_INCREMENT NOT NULL, trainer_id INT DEFAULT NULL, date DATE NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, is_available TINYINT(1) DEFAULT 1 NOT NULL, INDEX IDX_B1C70B3CFB08EDF6 (trainer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE availability_slots (id INT AUTO_INCREMENT NOT NULL, trainer_id INT DEFAULT NULL, date DATE NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, INDEX IDX_CA56094FB08EDF6 (trainer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE schedule_templates (id INT AUTO_INCREMENT NOT NULL, trainer_id INT DEFAULT NULL, week_day INT NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, block_time INT NOT NULL, INDEX IDX_A02E03FEFB08EDF6 (trainer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE appointments ADD CONSTRAINT FK_6A41727AFB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainers (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE appointments ADD CONSTRAINT FK_6A41727A19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE appointments ADD CONSTRAINT FK_6A41727AD6F1FA37 FOREIGN KEY (availability_slot_id) REFERENCES availability_slots (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE availability_overrides ADD CONSTRAINT FK_B1C70B3CFB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainers (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE availability_slots ADD CONSTRAINT FK_CA56094FB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainers (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule_templates ADD CONSTRAINT FK_A02E03FEFB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainers (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE appointments DROP FOREIGN KEY FK_6A41727AFB08EDF6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE appointments DROP FOREIGN KEY FK_6A41727A19EB6921
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE appointments DROP FOREIGN KEY FK_6A41727AD6F1FA37
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE availability_overrides DROP FOREIGN KEY FK_B1C70B3CFB08EDF6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE availability_slots DROP FOREIGN KEY FK_CA56094FB08EDF6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE schedule_templates DROP FOREIGN KEY FK_A02E03FEFB08EDF6
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE appointments
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE availability_overrides
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE availability_slots
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE schedule_templates
        SQL);
    }
}
