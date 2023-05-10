<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504114914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY planning_ibfk_1');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6A6E137F2 FOREIGN KEY (idcir) REFERENCES circuit (idcircuit)');
        $this->addSql('ALTER TABLE station DROP FOREIGN KEY station_ibfk_1');
        $this->addSql('ALTER TABLE station ADD CONSTRAINT FK_9F39F8B1CC21A1C8 FOREIGN KEY (idcircuit) REFERENCES circuit (idcircuit)');
        $this->addSql('ALTER TABLE utilisateur ADD createdatu VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6A6E137F2');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT planning_ibfk_1 FOREIGN KEY (idcir) REFERENCES circuit (idcircuit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE station DROP FOREIGN KEY FK_9F39F8B1CC21A1C8');
        $this->addSql('ALTER TABLE station ADD CONSTRAINT station_ibfk_1 FOREIGN KEY (idcircuit) REFERENCES circuit (idcircuit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur DROP createdatu');
    }
}
