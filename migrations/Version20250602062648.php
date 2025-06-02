<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602062648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE clients DROP FOREIGN KEY FK_C82E7419EB6921
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C82E7419EB6921 ON clients
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE clients CHANGE client_id trainer_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE clients ADD CONSTRAINT FK_C82E74FB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainers (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C82E74FB08EDF6 ON clients (trainer_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE clients DROP FOREIGN KEY FK_C82E74FB08EDF6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C82E74FB08EDF6 ON clients
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE clients CHANGE trainer_id client_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE clients ADD CONSTRAINT FK_C82E7419EB6921 FOREIGN KEY (client_id) REFERENCES trainers (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C82E7419EB6921 ON clients (client_id)
        SQL);
    }
}
