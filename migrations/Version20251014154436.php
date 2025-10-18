<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251014154436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD customer_name VARCHAR(255) NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE order_product ADD price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE stock DROP INDEX UNIQ_4B3656604584665A, ADD INDEX IDX_4B3656604584665A (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP INDEX IDX_4B3656604584665A, ADD UNIQUE INDEX UNIQ_4B3656604584665A (product_id)');
        $this->addSql('ALTER TABLE order_product DROP price');
        $this->addSql('ALTER TABLE `order` DROP customer_name, DROP address, DROP created_at');
    }
}
