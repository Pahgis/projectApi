<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119152541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, tache_id INT DEFAULT NULL, user_id INT NOT NULL, content VARCHAR(255) NOT NULL, INDEX IDX_67F068BCD2235D39 (tache_id), INDEX IDX_67F068BCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste (id INT AUTO_INCREMENT NOT NULL, projet_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_FCF22AF4C18272 (projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', share TINYINT(1) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet_user (projet_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FA413966C18272 (projet_id), INDEX IDX_FA413966A76ED395 (user_id), PRIMARY KEY(projet_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tache (id INT AUTO_INCREMENT NOT NULL, liste_id INT DEFAULT NULL, member_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_preview DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', priority VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, share TINYINT(1) NOT NULL, INDEX IDX_93872075E85441D8 (liste_id), INDEX IDX_938720757597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCD2235D39 FOREIGN KEY (tache_id) REFERENCES tache (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE liste ADD CONSTRAINT FK_FCF22AF4C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE projet_user ADD CONSTRAINT FK_FA413966C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_user ADD CONSTRAINT FK_FA413966A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_93872075E85441D8 FOREIGN KEY (liste_id) REFERENCES liste (id)');
        $this->addSql('ALTER TABLE tache ADD CONSTRAINT FK_938720757597D3FE FOREIGN KEY (member_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCD2235D39');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE liste DROP FOREIGN KEY FK_FCF22AF4C18272');
        $this->addSql('ALTER TABLE projet_user DROP FOREIGN KEY FK_FA413966C18272');
        $this->addSql('ALTER TABLE projet_user DROP FOREIGN KEY FK_FA413966A76ED395');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_93872075E85441D8');
        $this->addSql('ALTER TABLE tache DROP FOREIGN KEY FK_938720757597D3FE');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE liste');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE projet_user');
        $this->addSql('DROP TABLE tache');
        $this->addSql('DROP TABLE user');
    }
}
