<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240930233359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD host_id INT NOT NULL, ADD city_id INT NOT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A1FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_AC74095A1FB8D185 ON activity (host_id)');
        $this->addSql('CREATE INDEX IDX_AC74095A8BAC62AF ON activity (city_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A1FB8D185');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A8BAC62AF');
        $this->addSql('DROP INDEX IDX_AC74095A1FB8D185 ON activity');
        $this->addSql('DROP INDEX IDX_AC74095A8BAC62AF ON activity');
        $this->addSql('ALTER TABLE activity DROP host_id, DROP city_id');
    }
}
