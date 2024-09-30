<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240930140853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accommodation (id INT AUTO_INCREMENT NOT NULL, host_id INT NOT NULL, city_id INT NOT NULL, title VARCHAR(150) NOT NULL, description LONGTEXT NOT NULL, address VARCHAR(200) NOT NULL, price_night DOUBLE PRECISION NOT NULL, nb_guests INT NOT NULL, nb_rooms INT NOT NULL, create_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', property_type VARCHAR(255) NOT NULL, INDEX IDX_2D3854121FB8D185 (host_id), INDEX IDX_2D3854128BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(200) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, create_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', address VARCHAR(255) NOT NULL, INDEX IDX_AC74095A12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(150) NOT NULL, postal_code INT NOT NULL, INDEX IDX_2D5B0234F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, status_id INT DEFAULT NULL, nb_transaction INT NOT NULL, payment_method VARCHAR(150) NOT NULL, payment_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6D28840D6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, accommodation_id INT NOT NULL, activity_id INT NOT NULL, image_url VARCHAR(200) NOT NULL, INDEX IDX_16DB4F898F3692CD (accommodation_id), INDEX IDX_16DB4F8981C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, departure_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', return_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', nb_adults INT NOT NULL, nb_children INT DEFAULT NULL, date_creation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_modification DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', total DOUBLE PRECISION NOT NULL, INDEX IDX_42C849556BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_activity (reservation_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_31C1EB4EB83297E7 (reservation_id), INDEX IDX_31C1EB4E81C06096 (activity_id), PRIMARY KEY(reservation_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_status (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, activity_id INT DEFAULT NULL, accommodation_id INT DEFAULT NULL, note VARCHAR(50) NOT NULL, comment LONGTEXT NOT NULL, date_re_view DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_794381C69395C3F3 (customer_id), INDEX IDX_794381C681C06096 (activity_id), INDEX IDX_794381C68F3692CD (accommodation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status_payment (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, age VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date_birth DATE NOT NULL, phone VARCHAR(20) NOT NULL, country VARCHAR(100) NOT NULL, city VARCHAR(50) NOT NULL, address VARCHAR(100) NOT NULL, gender VARCHAR(255) NOT NULL, first_name VARCHAR(150) NOT NULL, last_name VARCHAR(200) NOT NULL, UNIQUE INDEX UNIQ_D95AB405A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accommodation ADD CONSTRAINT FK_2D3854121FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE accommodation ADD CONSTRAINT FK_2D3854128BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D6BF700BD FOREIGN KEY (status_id) REFERENCES status_payment (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F898F3692CD FOREIGN KEY (accommodation_id) REFERENCES accommodation (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8981C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556BF700BD FOREIGN KEY (status_id) REFERENCES reservation_status (id)');
        $this->addSql('ALTER TABLE reservation_activity ADD CONSTRAINT FK_31C1EB4EB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_activity ADD CONSTRAINT FK_31C1EB4E81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C69395C3F3 FOREIGN KEY (customer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C681C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C68F3692CD FOREIGN KEY (accommodation_id) REFERENCES accommodation (id)');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB405A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE accommodation DROP FOREIGN KEY FK_2D3854121FB8D185');
        $this->addSql('ALTER TABLE accommodation DROP FOREIGN KEY FK_2D3854128BAC62AF');
        $this->addSql('ALTER TABLE activity DROP FOREIGN KEY FK_AC74095A12469DE2');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234F92F3E70');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D6BF700BD');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F898F3692CD');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8981C06096');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556BF700BD');
        $this->addSql('ALTER TABLE reservation_activity DROP FOREIGN KEY FK_31C1EB4EB83297E7');
        $this->addSql('ALTER TABLE reservation_activity DROP FOREIGN KEY FK_31C1EB4E81C06096');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C69395C3F3');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C681C06096');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C68F3692CD');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB405A76ED395');
        $this->addSql('DROP TABLE accommodation');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservation_activity');
        $this->addSql('DROP TABLE reservation_status');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE status_payment');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_profile');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
