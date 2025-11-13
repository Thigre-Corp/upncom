<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251113133953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6629058553');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6629058553 FOREIGN KEY (image_deux_id) REFERENCES image (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6629058553');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6629058553 FOREIGN KEY (image_deux_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
