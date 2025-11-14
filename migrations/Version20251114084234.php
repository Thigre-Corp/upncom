<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114084234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service ADD image_service_id INT DEFAULT NULL, DROP image_couverture_url');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD28182BED0 FOREIGN KEY (image_service_id) REFERENCES image (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_E19D9AD28182BED0 ON service (image_service_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD28182BED0');
        $this->addSql('DROP INDEX IDX_E19D9AD28182BED0 ON service');
        $this->addSql('ALTER TABLE service ADD image_couverture_url VARCHAR(255) DEFAULT NULL, DROP image_service_id');
    }
}
