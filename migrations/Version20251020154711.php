<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251020154711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE container (id INT AUTO_INCREMENT NOT NULL, parent_order_id INT NOT NULL, INDEX IDX_C7A2EC1B1252C1E9 (parent_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1B1252C1E9 FOREIGN KEY (parent_order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE rider_orders CHANGE container_id container_code INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1B1252C1E9');
        $this->addSql('DROP TABLE container');
        $this->addSql('ALTER TABLE rider_orders CHANGE container_code container_id INT NOT NULL');
    }
}
