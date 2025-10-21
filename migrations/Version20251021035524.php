<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251021035524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE container (id INT AUTO_INCREMENT NOT NULL, orders_id INT NOT NULL, parent_order_id INT NOT NULL, rider_id INT DEFAULT NULL, rider_order_id INT DEFAULT NULL, riders_id INT DEFAULT NULL, user_id INT NOT NULL, status VARCHAR(50) NOT NULL, INDEX IDX_C7A2EC1B1252C1E9 (parent_order_id), INDEX IDX_C7A2EC1BFF881F6 (rider_id), INDEX IDX_C7A2EC1BCFFE9AD6 (orders_id), INDEX IDX_C7A2EC1BEEE1DAC7 (rider_order_id), INDEX IDX_C7A2EC1BB3A0128D (riders_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE containers (id INT AUTO_INCREMENT NOT NULL, order_id INT NOT NULL, rider_id INT NOT NULL, delivered_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', notes LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rider (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, phone VARCHAR(20) DEFAULT NULL, status VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rider_order (id INT AUTO_INCREMENT NOT NULL, rider_id INT DEFAULT NULL, status VARCHAR(50) NOT NULL, pickup_time DATETIME NOT NULL, delivered_at DATETIME NOT NULL, container_id INT NOT NULL, container_status VARCHAR(50) NOT NULL, INDEX IDX_CC9B14B6FF881F6 (rider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rider_orders (id INT AUTO_INCREMENT NOT NULL, rider_id INT NOT NULL, order_id INT NOT NULL, status VARCHAR(50) NOT NULL, pickup_time DATETIME NOT NULL, delivered_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', container_code INT NOT NULL, container_status VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE riders (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, phone VARCHAR(20) DEFAULT NULL, status VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1B1252C1E9 FOREIGN KEY (parent_order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BFF881F6 FOREIGN KEY (rider_id) REFERENCES riders (id)');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BEEE1DAC7 FOREIGN KEY (rider_order_id) REFERENCES rider_order (id)');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BB3A0128D FOREIGN KEY (riders_id) REFERENCES rider (id)');
        $this->addSql('ALTER TABLE rider_order ADD CONSTRAINT FK_CC9B14B6FF881F6 FOREIGN KEY (rider_id) REFERENCES rider (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1B1252C1E9');
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1BFF881F6');
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1BCFFE9AD6');
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1BEEE1DAC7');
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1BB3A0128D');
        $this->addSql('ALTER TABLE rider_order DROP FOREIGN KEY FK_CC9B14B6FF881F6');
        $this->addSql('DROP TABLE container');
        $this->addSql('DROP TABLE containers');
        $this->addSql('DROP TABLE rider');
        $this->addSql('DROP TABLE rider_order');
        $this->addSql('DROP TABLE rider_orders');
        $this->addSql('DROP TABLE riders');
    }
}
