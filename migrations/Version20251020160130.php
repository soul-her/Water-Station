<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251020160130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE container ADD rider_id INT DEFAULT NULL, ADD orders_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BFF881F6 FOREIGN KEY (rider_id) REFERENCES riders (id)');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('CREATE INDEX IDX_C7A2EC1BFF881F6 ON container (rider_id)');
        $this->addSql('CREATE INDEX IDX_C7A2EC1BCFFE9AD6 ON container (orders_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1BFF881F6');
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1BCFFE9AD6');
        $this->addSql('DROP INDEX IDX_C7A2EC1BFF881F6 ON container');
        $this->addSql('DROP INDEX IDX_C7A2EC1BCFFE9AD6 ON container');
        $this->addSql('ALTER TABLE container DROP rider_id, DROP orders_id');
    }
}
