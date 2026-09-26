<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250820132819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Translation entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE translation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_type VARCHAR(255) NOT NULL, object_id VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, field VARCHAR(255) NOT NULL, value CLOB NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE translation');
    }
}
