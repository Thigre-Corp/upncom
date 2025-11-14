<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114094252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE realisation DROP FOREIGN KEY FK_EAA5610E7B0478FE');
        $this->addSql('ALTER TABLE realisation ADD CONSTRAINT FK_EAA5610E7B0478FE FOREIGN KEY (image_couverture_id) REFERENCES image (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE realisation DROP FOREIGN KEY FK_EAA5610E7B0478FE');
        $this->addSql('ALTER TABLE realisation ADD CONSTRAINT FK_EAA5610E7B0478FE FOREIGN KEY (image_couverture_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
