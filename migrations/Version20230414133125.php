<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414133125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id INT AUTO_INCREMENT NOT NULL, region VARCHAR(20) NOT NULL, cite VARCHAR(20) NOT NULL, rue VARCHAR(20) NOT NULL, numposte INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utilisateur ADD nomu VARCHAR(255) NOT NULL, ADD prenomu VARCHAR(255) NOT NULL, ADD telephoneu INT NOT NULL, ADD roleu VARCHAR(255) NOT NULL, ADD cinu INT NOT NULL, ADD imagepu VARCHAR(500) DEFAULT NULL, ADD abonneu TINYINT(1) DEFAULT NULL, ADD idAdresse INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3D3E663E4 FOREIGN KEY (idAdresse) REFERENCES adresse (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B394C0B4C4 ON utilisateur (cinu)');
        $this->addSql('CREATE INDEX IDX_1D1C63B3D3E663E4 ON utilisateur (idAdresse)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3D3E663E4');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP INDEX UNIQ_1D1C63B394C0B4C4 ON utilisateur');
        $this->addSql('DROP INDEX IDX_1D1C63B3D3E663E4 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP nomu, DROP prenomu, DROP telephoneu, DROP roleu, DROP cinu, DROP imagepu, DROP abonneu, DROP idAdresse');
    }
}
