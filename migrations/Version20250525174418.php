<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250525174418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE appointments DROP INDEX UNIQ_6A41727AD6F1FA37, ADD INDEX IDX_6A41727AD6F1FA37 (availability_slot_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE appointments DROP INDEX IDX_6A41727AD6F1FA37, ADD UNIQUE INDEX UNIQ_6A41727AD6F1FA37 (availability_slot_id)
        SQL);
    }
}
