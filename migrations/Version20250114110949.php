<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250114110949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP INDEX UNIQ_527EDB256BF700BD, ADD INDEX IDX_527EDB256BF700BD (status_id)');
        $this->addSql('ALTER TABLE task DROP INDEX UNIQ_527EDB258C03F15C, ADD INDEX IDX_527EDB258C03F15C (employee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP INDEX IDX_527EDB258C03F15C, ADD UNIQUE INDEX UNIQ_527EDB258C03F15C (employee_id)');
        $this->addSql('ALTER TABLE task DROP INDEX IDX_527EDB256BF700BD, ADD UNIQUE INDEX UNIQ_527EDB256BF700BD (status_id)');
    }
}
