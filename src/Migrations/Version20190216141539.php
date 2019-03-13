<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190216141539 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, movie_id INT DEFAULT NULL, series_id INT DEFAULT NULL, humor_id INT DEFAULT NULL, content LONGTEXT NOT NULL, creation_date DATETIME NOT NULL, INDEX IDX_5F9E962AA76ED395 (user_id), INDEX IDX_5F9E962A8F93B6FC (movie_id), INDEX IDX_5F9E962A5278319C (series_id), INDEX IDX_5F9E962A2F4213C6 (humor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE humor (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, INDEX IDX_2FE0A434A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movies (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, introduction LONGTEXT NOT NULL, INDEX IDX_C61EED30A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_users (role_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_E35F4DC6D60322AC (role_id), INDEX IDX_E35F4DC667B3B43D (users_id), PRIMARY KEY(role_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE series (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, introduction LONGTEXT NOT NULL, INDEX IDX_3A10012DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shootings (id INT AUTO_INCREMENT NOT NULL, movie_id INT DEFAULT NULL, serie_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, lat DOUBLE PRECISION NOT NULL, lng DOUBLE PRECISION NOT NULL, INDEX IDX_43F014798F93B6FC (movie_id), INDEX IDX_43F01479D94388BD (serie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A8F93B6FC FOREIGN KEY (movie_id) REFERENCES movies (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A5278319C FOREIGN KEY (series_id) REFERENCES series (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A2F4213C6 FOREIGN KEY (humor_id) REFERENCES humor (id)');
        $this->addSql('ALTER TABLE humor ADD CONSTRAINT FK_2FE0A434A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE movies ADD CONSTRAINT FK_C61EED30A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE role_users ADD CONSTRAINT FK_E35F4DC6D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_users ADD CONSTRAINT FK_E35F4DC667B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE series ADD CONSTRAINT FK_3A10012DA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE shootings ADD CONSTRAINT FK_43F014798F93B6FC FOREIGN KEY (movie_id) REFERENCES movies (id)');
        $this->addSql('ALTER TABLE shootings ADD CONSTRAINT FK_43F01479D94388BD FOREIGN KEY (serie_id) REFERENCES series (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A2F4213C6');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A8F93B6FC');
        $this->addSql('ALTER TABLE shootings DROP FOREIGN KEY FK_43F014798F93B6FC');
        $this->addSql('ALTER TABLE role_users DROP FOREIGN KEY FK_E35F4DC6D60322AC');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A5278319C');
        $this->addSql('ALTER TABLE shootings DROP FOREIGN KEY FK_43F01479D94388BD');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE humor DROP FOREIGN KEY FK_2FE0A434A76ED395');
        $this->addSql('ALTER TABLE movies DROP FOREIGN KEY FK_C61EED30A76ED395');
        $this->addSql('ALTER TABLE role_users DROP FOREIGN KEY FK_E35F4DC667B3B43D');
        $this->addSql('ALTER TABLE series DROP FOREIGN KEY FK_3A10012DA76ED395');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE humor');
        $this->addSql('DROP TABLE movies');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_users');
        $this->addSql('DROP TABLE series');
        $this->addSql('DROP TABLE shootings');
        $this->addSql('DROP TABLE users');
    }
}
