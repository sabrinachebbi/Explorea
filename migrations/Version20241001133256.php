<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001133256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8981C06096');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F898F3692CD');
        $this->addSql('DROP INDEX IDX_16DB4F8981C06096 ON picture');
        $this->addSql('DROP INDEX IDX_16DB4F898F3692CD ON picture');
        $this->addSql('ALTER TABLE picture ADD accommodation_pictures_id INT DEFAULT NULL, ADD activity_pictures_id INT DEFAULT NULL, DROP accommodation_id, DROP activity_id');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89C2229E86 FOREIGN KEY (accommodation_pictures_id) REFERENCES accommodation (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89B2CF3C89 FOREIGN KEY (activity_pictures_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F89C2229E86 ON picture (accommodation_pictures_id)');
        $this->addSql('CREATE INDEX IDX_16DB4F89B2CF3C89 ON picture (activity_pictures_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89C2229E86');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89B2CF3C89');
        $this->addSql('DROP INDEX IDX_16DB4F89C2229E86 ON picture');
        $this->addSql('DROP INDEX IDX_16DB4F89B2CF3C89 ON picture');
        $this->addSql('ALTER TABLE picture ADD accommodation_id INT DEFAULT NULL, ADD activity_id INT DEFAULT NULL, DROP accommodation_pictures_id, DROP activity_pictures_id');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8981C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F898F3692CD FOREIGN KEY (accommodation_id) REFERENCES accommodation (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_16DB4F8981C06096 ON picture (activity_id)');
        $this->addSql('CREATE INDEX IDX_16DB4F898F3692CD ON picture (accommodation_id)');
    }
}
