<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240410085838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498CF8B322');
        $this->addSql('DROP INDEX UNIQ_8D93D6498CF8B322 ON user');
        $this->addSql('ALTER TABLE user DROP user_buyer_id');
        $this->addSql('ALTER TABLE user_buyer ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_buyer ADD CONSTRAINT FK_6652A4EBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6652A4EBA76ED395 ON user_buyer (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD user_buyer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498CF8B322 FOREIGN KEY (user_buyer_id) REFERENCES user_buyer (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6498CF8B322 ON user (user_buyer_id)');
        $this->addSql('ALTER TABLE user_buyer DROP FOREIGN KEY FK_6652A4EBA76ED395');
        $this->addSql('DROP INDEX UNIQ_6652A4EBA76ED395 ON user_buyer');
        $this->addSql('ALTER TABLE user_buyer DROP user_id');
    }
}
