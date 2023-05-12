<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505222535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mentioned_in (users_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_922DB30A67B3B43D (users_id), INDEX IDX_922DB30A1EBAF6CC (articles_id), PRIMARY KEY(users_id, articles_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consults (users_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_55474E9667B3B43D (users_id), INDEX IDX_55474E961EBAF6CC (articles_id), PRIMARY KEY(users_id, articles_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mentioned_in ADD CONSTRAINT FK_922DB30A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mentioned_in ADD CONSTRAINT FK_922DB30A1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE consults ADD CONSTRAINT FK_55474E9667B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE consults ADD CONSTRAINT FK_55474E961EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_articles DROP FOREIGN KEY FK_C49C1AB21EBAF6CC');
        $this->addSql('ALTER TABLE users_articles DROP FOREIGN KEY FK_C49C1AB267B3B43D');
        $this->addSql('DROP TABLE users_articles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_articles (users_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_C49C1AB267B3B43D (users_id), INDEX IDX_C49C1AB21EBAF6CC (articles_id), PRIMARY KEY(users_id, articles_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE users_articles ADD CONSTRAINT FK_C49C1AB21EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_articles ADD CONSTRAINT FK_C49C1AB267B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mentioned_in DROP FOREIGN KEY FK_922DB30A67B3B43D');
        $this->addSql('ALTER TABLE mentioned_in DROP FOREIGN KEY FK_922DB30A1EBAF6CC');
        $this->addSql('ALTER TABLE consults DROP FOREIGN KEY FK_55474E9667B3B43D');
        $this->addSql('ALTER TABLE consults DROP FOREIGN KEY FK_55474E961EBAF6CC');
        $this->addSql('DROP TABLE mentioned_in');
        $this->addSql('DROP TABLE consults');
    }
}
