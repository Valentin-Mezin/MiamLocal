<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240406212748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish_list DROP FOREIGN KEY FK_5B8739BDB52083D3');
        $this->addSql('DROP INDEX IDX_5B8739BDB52083D3 ON wish_list');
        $this->addSql('ALTER TABLE wish_list CHANGE productct_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE wish_list ADD CONSTRAINT FK_5B8739BD4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_5B8739BD4584665A ON wish_list (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wish_list DROP FOREIGN KEY FK_5B8739BD4584665A');
        $this->addSql('DROP INDEX IDX_5B8739BD4584665A ON wish_list');
        $this->addSql('ALTER TABLE wish_list CHANGE product_id productct_id INT NOT NULL');
        $this->addSql('ALTER TABLE wish_list ADD CONSTRAINT FK_5B8739BDB52083D3 FOREIGN KEY (productct_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_5B8739BDB52083D3 ON wish_list (productct_id)');
    }
}
