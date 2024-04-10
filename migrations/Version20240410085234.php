<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240410085234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BE8CF8B322');
        $this->addSql('ALTER TABLE adress DROP FOREIGN KEY FK_5CECC7BE8D08AD3D');
        $this->addSql('DROP INDEX UNIQ_5CECC7BE8D08AD3D ON adress');
        $this->addSql('DROP INDEX UNIQ_5CECC7BE8CF8B322 ON adress');
        $this->addSql('ALTER TABLE adress DROP user_seller_id, DROP user_buyer_id');
        $this->addSql('ALTER TABLE user_buyer ADD adress_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_buyer ADD CONSTRAINT FK_6652A4EB8486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6652A4EB8486F9AC ON user_buyer (adress_id)');
        $this->addSql('ALTER TABLE user_seller ADD adress_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_seller ADD CONSTRAINT FK_9E48C8C18486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9E48C8C18486F9AC ON user_seller (adress_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adress ADD user_seller_id INT NOT NULL, ADD user_buyer_id INT NOT NULL');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE8CF8B322 FOREIGN KEY (user_buyer_id) REFERENCES user_buyer (id)');
        $this->addSql('ALTER TABLE adress ADD CONSTRAINT FK_5CECC7BE8D08AD3D FOREIGN KEY (user_seller_id) REFERENCES user_seller (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5CECC7BE8D08AD3D ON adress (user_seller_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5CECC7BE8CF8B322 ON adress (user_buyer_id)');
        $this->addSql('ALTER TABLE user_buyer DROP FOREIGN KEY FK_6652A4EB8486F9AC');
        $this->addSql('DROP INDEX UNIQ_6652A4EB8486F9AC ON user_buyer');
        $this->addSql('ALTER TABLE user_buyer DROP adress_id');
        $this->addSql('ALTER TABLE user_seller DROP FOREIGN KEY FK_9E48C8C18486F9AC');
        $this->addSql('DROP INDEX UNIQ_9E48C8C18486F9AC ON user_seller');
        $this->addSql('ALTER TABLE user_seller DROP adress_id');
    }
}
