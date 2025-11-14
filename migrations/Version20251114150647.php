<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251114150647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_realisation DROP FOREIGN KEY FK_EBEE443519EB6921');
        $this->addSql('ALTER TABLE client_realisation DROP FOREIGN KEY FK_EBEE4435B685E551');
        $this->addSql('DROP TABLE client_realisation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_realisation (client_id INT NOT NULL, realisation_id INT NOT NULL, INDEX IDX_EBEE443519EB6921 (client_id), INDEX IDX_EBEE4435B685E551 (realisation_id), PRIMARY KEY(client_id, realisation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE client_realisation ADD CONSTRAINT FK_EBEE443519EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_realisation ADD CONSTRAINT FK_EBEE4435B685E551 FOREIGN KEY (realisation_id) REFERENCES realisation (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
