<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251113134622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6629058553');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E663435B44A');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6691F8D062');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66967B27A2');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6629058553 FOREIGN KEY (image_deux_id) REFERENCES image (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E663435B44A FOREIGN KEY (image_trois_id) REFERENCES image (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6691F8D062 FOREIGN KEY (image_principale_id) REFERENCES image (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66967B27A2 FOREIGN KEY (image_quatre_id) REFERENCES image (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6691F8D062');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6629058553');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E663435B44A');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66967B27A2');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6691F8D062 FOREIGN KEY (image_principale_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6629058553 FOREIGN KEY (image_deux_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E663435B44A FOREIGN KEY (image_trois_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66967B27A2 FOREIGN KEY (image_quatre_id) REFERENCES image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
