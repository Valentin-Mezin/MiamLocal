<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407090815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_seller ADD user_seller_id INT NOT NULL');
        $this->addSql('ALTER TABLE media_seller ADD CONSTRAINT FK_476349228D08AD3D FOREIGN KEY (user_seller_id) REFERENCES user_seller (id)');
        $this->addSql('CREATE INDEX IDX_476349228D08AD3D ON media_seller (user_seller_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media_seller DROP FOREIGN KEY FK_476349228D08AD3D');
        $this->addSql('DROP INDEX IDX_476349228D08AD3D ON media_seller');
        $this->addSql('ALTER TABLE media_seller DROP user_seller_id');
    }
}
