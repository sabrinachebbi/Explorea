<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001081626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accommodation DROP FOREIGN KEY FK_2D3854128BAC62AF');
        $this->addSql('DROP INDEX IDX_2D3854128BAC62AF ON accommodation');
        $this->addSql('ALTER TABLE accommodation ADD country_id INT DEFAULT NULL, DROP city_id');
        $this->addSql('ALTER TABLE accommodation ADD CONSTRAINT FK_2D385412F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_2D385412F92F3E70 ON accommodation (country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accommodation DROP FOREIGN KEY FK_2D385412F92F3E70');
        $this->addSql('DROP INDEX IDX_2D385412F92F3E70 ON accommodation');
        $this->addSql('ALTER TABLE accommodation ADD city_id INT NOT NULL, DROP country_id');
        $this->addSql('ALTER TABLE accommodation ADD CONSTRAINT FK_2D3854128BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2D3854128BAC62AF ON accommodation (city_id)');
    }
}
