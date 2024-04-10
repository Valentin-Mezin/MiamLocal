<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240410081333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE label_user_seller (label_id INT NOT NULL, user_seller_id INT NOT NULL, INDEX IDX_7594183733B92F39 (label_id), INDEX IDX_759418378D08AD3D (user_seller_id), PRIMARY KEY(label_id, user_seller_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE label_user_seller ADD CONSTRAINT FK_7594183733B92F39 FOREIGN KEY (label_id) REFERENCES label (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE label_user_seller ADD CONSTRAINT FK_759418378D08AD3D FOREIGN KEY (user_seller_id) REFERENCES user_seller (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE label_user_seller DROP FOREIGN KEY FK_7594183733B92F39');
        $this->addSql('ALTER TABLE label_user_seller DROP FOREIGN KEY FK_759418378D08AD3D');
        $this->addSql('DROP TABLE label_user_seller');
    }
}
