<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602095218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE appointments ADD created_by_id INT DEFAULT NULL, ADD created_at DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE appointments ADD CONSTRAINT FK_6A41727AB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6A41727AB03A8386 ON appointments (created_by_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE appointments DROP FOREIGN KEY FK_6A41727AB03A8386
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6A41727AB03A8386 ON appointments
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE appointments DROP created_by_id, DROP created_at
        SQL);
    }
}
