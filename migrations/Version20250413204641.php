<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250413204641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, user_type_id INT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(180) NOT NULL, surname VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) DEFAULT 'tmp-password' NOT NULL, last_token VARCHAR(255) DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, reset_password_token VARCHAR(255) DEFAULT NULL, reset_password_requested_at DATETIME DEFAULT NULL, password_changed_at DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, confirmed_email TINYINT(1) DEFAULT 0 NOT NULL, confirmed_email_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_1483A5E99D419299 (user_type_id), INDEX IDX_1483A5E9B03A8386 (created_by_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E99D419299 FOREIGN KEY (user_type_id) REFERENCES user_types (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users ADD CONSTRAINT FK_1483A5E9B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)
        SQL);

        $this->addSql('INSERT IGNORE INTO user_types (name, code) VALUES
        ("Admin", "admin");
    ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E99D419299
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_types
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
    }
}
