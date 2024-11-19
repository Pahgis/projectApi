<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119192152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liste DROP FOREIGN KEY FK_FCF22AF4C18272');
        $this->addSql('DROP INDEX IDX_FCF22AF4C18272 ON liste');
        $this->addSql('ALTER TABLE liste CHANGE projet_id sprint_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE liste ADD CONSTRAINT FK_FCF22AF48C24077B FOREIGN KEY (sprint_id) REFERENCES sprint (id)');
        $this->addSql('CREATE INDEX IDX_FCF22AF48C24077B ON liste (sprint_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liste DROP FOREIGN KEY FK_FCF22AF48C24077B');
        $this->addSql('DROP INDEX IDX_FCF22AF48C24077B ON liste');
        $this->addSql('ALTER TABLE liste CHANGE sprint_id projet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE liste ADD CONSTRAINT FK_FCF22AF4C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FCF22AF4C18272 ON liste (projet_id)');
    }
}
