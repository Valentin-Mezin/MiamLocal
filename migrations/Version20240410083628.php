<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240410083628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, user_seller_id INT NOT NULL, user_buyer_id INT NOT NULL, adress VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, post_code INT NOT NULL, country VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_5CECC7BE8D08AD3D (user_seller_id), UNIQUE INDEX UNIQ_5CECC7BE8CF8B322 (user_buyer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_buyer (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, registration_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE8D08AD3D FOREIGN KEY (user_seller_id) REFERENCES user_seller (id)');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE8CF8B322 FOREIGN KEY (user_buyer_id) REFERENCES user_buyer (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BE8D08AD3D');
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BE8CF8B322');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE user_buyer');
    }
}
