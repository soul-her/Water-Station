<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251021054425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1BB3A0128D');
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1BCFFE9AD6');
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1BEEE1DAC7');
        $this->addSql('DROP INDEX IDX_C7A2EC1BEEE1DAC7 ON container');
        $this->addSql('DROP INDEX IDX_C7A2EC1BCFFE9AD6 ON container');
        $this->addSql('DROP INDEX IDX_C7A2EC1BB3A0128D ON container');
        $this->addSql('ALTER TABLE container ADD rider_id INT DEFAULT NULL, ADD pickup_time DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP rider_order_id, DROP riders_id, CHANGE user_id user_id INT DEFAULT NULL, CHANGE orders_id order_id INT NOT NULL');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1B8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BFF881F6 FOREIGN KEY (rider_id) REFERENCES rider (id)');
        $this->addSql('CREATE INDEX IDX_C7A2EC1B8D9F6D38 ON container (order_id)');
        $this->addSql('CREATE INDEX IDX_C7A2EC1BFF881F6 ON container (rider_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1B8D9F6D38');
        $this->addSql('ALTER TABLE container DROP FOREIGN KEY FK_C7A2EC1BFF881F6');
        $this->addSql('DROP INDEX IDX_C7A2EC1B8D9F6D38 ON container');
        $this->addSql('DROP INDEX IDX_C7A2EC1BFF881F6 ON container');
        $this->addSql('ALTER TABLE container ADD riders_id INT DEFAULT NULL, DROP pickup_time, DROP delivered_at, CHANGE user_id user_id INT NOT NULL, CHANGE order_id orders_id INT NOT NULL, CHANGE rider_id rider_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BB3A0128D FOREIGN KEY (riders_id) REFERENCES rider (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BCFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE container ADD CONSTRAINT FK_C7A2EC1BEEE1DAC7 FOREIGN KEY (rider_order_id) REFERENCES rider_order (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C7A2EC1BEEE1DAC7 ON container (rider_order_id)');
        $this->addSql('CREATE INDEX IDX_C7A2EC1BCFFE9AD6 ON container (orders_id)');
        $this->addSql('CREATE INDEX IDX_C7A2EC1BB3A0128D ON container (riders_id)');
    }
}
