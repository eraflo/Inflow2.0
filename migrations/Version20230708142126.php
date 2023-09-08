<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230708142126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE has DROP FOREIGN KEY FK_C6F39EA9A0035F3');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscribed (users_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_59D4EE3867B3B43D (users_id), INDEX IDX_59D4EE38A21214B7 (categories_id), PRIMARY KEY(users_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscribed ADD CONSTRAINT FK_59D4EE3867B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscribed ADD CONSTRAINT FK_59D4EE38A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suscribed DROP FOREIGN KEY FK_444DEDC367B3B43D');
        $this->addSql('ALTER TABLE suscribed DROP FOREIGN KEY FK_444DEDC3A21214B7');
        $this->addSql('DROP TABLE ranks');
        $this->addSql('DROP TABLE suscribed');
        $this->addSql('ALTER TABLE articles CHANGE version version DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('DROP INDEX IDX_C6F39EA9A0035F3 ON has');
        $this->addSql('DROP INDEX `primary` ON has');
        $this->addSql('ALTER TABLE has CHANGE ranks_id roles_id INT NOT NULL');
        $this->addSql('ALTER TABLE has ADD CONSTRAINT FK_C6F39EA38C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_C6F39EA38C751C4 ON has (roles_id)');
        $this->addSql('ALTER TABLE has ADD PRIMARY KEY (users_id, roles_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE has DROP FOREIGN KEY FK_C6F39EA38C751C4');
        $this->addSql('CREATE TABLE ranks (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE suscribed (users_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_444DEDC367B3B43D (users_id), INDEX IDX_444DEDC3A21214B7 (categories_id), PRIMARY KEY(users_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE suscribed ADD CONSTRAINT FK_444DEDC367B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suscribed ADD CONSTRAINT FK_444DEDC3A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscribed DROP FOREIGN KEY FK_59D4EE3867B3B43D');
        $this->addSql('ALTER TABLE subscribed DROP FOREIGN KEY FK_59D4EE38A21214B7');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE subscribed');
        $this->addSql('ALTER TABLE articles CHANGE version version DOUBLE PRECISION DEFAULT \'1\'');
        $this->addSql('DROP INDEX IDX_C6F39EA38C751C4 ON has');
        $this->addSql('DROP INDEX `PRIMARY` ON has');
        $this->addSql('ALTER TABLE has CHANGE roles_id ranks_id INT NOT NULL');
        $this->addSql('ALTER TABLE has ADD CONSTRAINT FK_C6F39EA9A0035F3 FOREIGN KEY (ranks_id) REFERENCES ranks (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_C6F39EA9A0035F3 ON has (ranks_id)');
        $this->addSql('ALTER TABLE has ADD PRIMARY KEY (users_id, ranks_id)');
        $this->addSql('ALTER TABLE users DROP roles');
    }
}
