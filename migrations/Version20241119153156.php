<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119153156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tache_user (tache_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FFA0B20FD2235D39 (tache_id), INDEX IDX_FFA0B20FA76ED395 (user_id), PRIMARY KEY(tache_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tache_user ADD CONSTRAINT FK_FFA0B20FD2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache_user ADD CONSTRAINT FK_FFA0B20FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_938720757597D3FE');
        $this->addSql('DROP INDEX IDX_938720757597D3FE ON tache');
        $this->addSql('ALTER TABLE tache DROP member_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tache_user DROP FOREIGN KEY FK_FFA0B20FD2235D39');
        $this->addSql('ALTER TABLE tache_user DROP FOREIGN KEY FK_FFA0B20FA76ED395');
        $this->addSql('DROP TABLE tache_user');
        $this->addSql('ALTER TABLE tache ADD member_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_938720757597D3FE FOREIGN KEY (member_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_938720757597D3FE ON tache (member_id)');
    }
}
