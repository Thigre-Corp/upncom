<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114095438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_realisation (client_id INT NOT NULL, realisation_id INT NOT NULL, INDEX IDX_EBEE443519EB6921 (client_id), INDEX IDX_EBEE4435B685E551 (realisation_id), PRIMARY KEY(client_id, realisation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE realisation_tag (realisation_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_74873B97B685E551 (realisation_id), INDEX IDX_74873B97BAD26311 (tag_id), PRIMARY KEY(realisation_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_realisation ADD CONSTRAINT FK_EBEE443519EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_realisation ADD CONSTRAINT FK_EBEE4435B685E551 FOREIGN KEY (realisation_id) REFERENCES realisation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE realisation_tag ADD CONSTRAINT FK_74873B97B685E551 FOREIGN KEY (realisation_id) REFERENCES realisation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE realisation_tag ADD CONSTRAINT FK_74873B97BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client ADD image_client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455C169B275 FOREIGN KEY (image_client_id) REFERENCES image (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C7440455C169B275 ON client (image_client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_realisation DROP FOREIGN KEY FK_EBEE443519EB6921');
        $this->addSql('ALTER TABLE client_realisation DROP FOREIGN KEY FK_EBEE4435B685E551');
        $this->addSql('ALTER TABLE realisation_tag DROP FOREIGN KEY FK_74873B97B685E551');
        $this->addSql('ALTER TABLE realisation_tag DROP FOREIGN KEY FK_74873B97BAD26311');
        $this->addSql('DROP TABLE client_realisation');
        $this->addSql('DROP TABLE realisation_tag');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455C169B275');
        $this->addSql('DROP INDEX IDX_C7440455C169B275 ON client');
        $this->addSql('ALTER TABLE client DROP image_client_id');
    }
}
