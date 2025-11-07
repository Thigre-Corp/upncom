<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251107083634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, client_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, media_url VARCHAR(255) DEFAULT NULL, alt_text VARCHAR(255) DEFAULT NULL, mask_svg VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisation (id INT AUTO_INCREMENT NOT NULL, image_couverture_id INT DEFAULT NULL, accroche VARCHAR(255) NOT NULL, resume_mission LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_EAA5610E7B0478FE (image_couverture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisation_image (realisation_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_F9D6B0FB685E551 (realisation_id), INDEX IDX_F9D6B0F3DA5256D (image_id), PRIMARY KEY(realisation_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE realisation ADD CONSTRAINT FK_EAA5610E7B0478FE FOREIGN KEY (image_couverture_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE realisation_image ADD CONSTRAINT FK_F9D6B0FB685E551 FOREIGN KEY (realisation_id) REFERENCES realisation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE realisation_image ADD CONSTRAINT FK_F9D6B0F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD is_published TINYINT(1) NOT NULL, DROP media_url');
        $this->addSql('ALTER TABLE article_image ADD CONSTRAINT FK_B28A764E3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_image DROP FOREIGN KEY FK_B28A764E3DA5256D');
        $this->addSql('ALTER TABLE realisation DROP FOREIGN KEY FK_EAA5610E7B0478FE');
        $this->addSql('ALTER TABLE realisation_image DROP FOREIGN KEY FK_F9D6B0FB685E551');
        $this->addSql('ALTER TABLE realisation_image DROP FOREIGN KEY FK_F9D6B0F3DA5256D');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE realisation');
        $this->addSql('DROP TABLE realisation_image');
        $this->addSql('ALTER TABLE article ADD media_url VARCHAR(255) DEFAULT NULL, DROP is_published');
    }
}
