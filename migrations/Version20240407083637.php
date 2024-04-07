<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407083637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_seller ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_seller ADD CONSTRAINT FK_9E48C8C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9E48C8C1A76ED395 ON user_seller (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_seller DROP FOREIGN KEY FK_9E48C8C1A76ED395');
        $this->addSql('DROP INDEX UNIQ_9E48C8C1A76ED395 ON user_seller');
        $this->addSql('ALTER TABLE user_seller DROP user_id');
    }
}
