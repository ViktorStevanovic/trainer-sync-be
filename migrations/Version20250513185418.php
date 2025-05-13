<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250513185418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD trainer_id INT DEFAULT NULL, ADD client_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9FB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainers (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E919EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_1483A5E9FB08EDF6 ON users (trainer_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_1483A5E919EB6921 ON users (client_id)
        SQL);
        $this->addSql(<<<'SQL'
            UPDATE users u
            INNER JOIN trainers t ON t.user_id = u.id
            SET u.trainer_id = t.id
        SQL);
        $this->addSql(<<<'SQL'
            UPDATE users u
            INNER JOIN clients c ON c.user_id = u.id
            SET u.client_id = c.id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9FB08EDF6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E919EB6921
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_1483A5E9FB08EDF6 ON users
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_1483A5E919EB6921 ON users
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP trainer_id, DROP client_id
        SQL);
    }
}
