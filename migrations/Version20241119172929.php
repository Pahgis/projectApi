<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119172929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sprint (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_preview_end DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', description VARCHAR(255) DEFAULT NULL, INDEX IDX_EF8055B7166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sprint ADD CONSTRAINT FK_EF8055B7166D1F9C FOREIGN KEY (project_id) REFERENCES projet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sprint DROP FOREIGN KEY FK_EF8055B7166D1F9C');
        $this->addSql('DROP TABLE sprint');
        $this->addSql('DROP TABLE tag');
    }
}
