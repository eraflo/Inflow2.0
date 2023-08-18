<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230601111257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(30) NOT NULL, content VARCHAR(5000) NOT NULL, release_date DATE NOT NULL, description VARCHAR(200) NOT NULL, INDEX IDX_BFDD3168A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mentions (articles_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_FE39735F1EBAF6CC (articles_id), INDEX IDX_FE39735F67B3B43D (users_id), PRIMARY KEY(articles_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE includes (articles_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_611438CF1EBAF6CC (articles_id), INDEX IDX_611438CFA21214B7 (categories_id), PRIMARY KEY(articles_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE concerns (articles_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_C32BFA2A1EBAF6CC (articles_id), INDEX IDX_C32BFA2A8D7B4FB4 (tags_id), PRIMARY KEY(articles_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, sub_id INT DEFAULT NULL, name VARCHAR(30) NOT NULL, img_path VARCHAR(255) NOT NULL, INDEX IDX_3AF3466856992D9 (sub_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opinions (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, article_id INT NOT NULL, opinion_value DOUBLE PRECISION NOT NULL, INDEX IDX_BEAF78D0A76ED395 (user_id), INDEX IDX_BEAF78D07294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preferences (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, setting_value VARCHAR(200) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ranks (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE socials (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, network VARCHAR(20) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_68A3B869A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(20) NOT NULL, url VARCHAR(200) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consults (users_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_55474E9667B3B43D (users_id), INDEX IDX_55474E961EBAF6CC (articles_id), PRIMARY KEY(users_id, articles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE follows (users_source INT NOT NULL, users_target INT NOT NULL, INDEX IDX_4B638A73506DF1E3 (users_source), INDEX IDX_4B638A734988A16C (users_target), PRIMARY KEY(users_source, users_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE set_ (users_id INT NOT NULL, preferences_id INT NOT NULL, INDEX IDX_A655293267B3B43D (users_id), INDEX IDX_A65529327CCD6FB7 (preferences_id), PRIMARY KEY(users_id, preferences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE has (users_id INT NOT NULL, ranks_id INT NOT NULL, INDEX IDX_C6F39EA67B3B43D (users_id), INDEX IDX_C6F39EA9A0035F3 (ranks_id), PRIMARY KEY(users_id, ranks_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suscribed (users_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_444DEDC367B3B43D (users_id), INDEX IDX_444DEDC3A21214B7 (categories_id), PRIMARY KEY(users_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE mentions ADD CONSTRAINT FK_FE39735F1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mentions ADD CONSTRAINT FK_FE39735F67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE includes ADD CONSTRAINT FK_611438CF1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE includes ADD CONSTRAINT FK_611438CFA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE concerns ADD CONSTRAINT FK_C32BFA2A1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE concerns ADD CONSTRAINT FK_C32BFA2A8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF3466856992D9 FOREIGN KEY (sub_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE opinions ADD CONSTRAINT FK_BEAF78D0A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE opinions ADD CONSTRAINT FK_BEAF78D07294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE socials ADD CONSTRAINT FK_68A3B869A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE consults ADD CONSTRAINT FK_55474E9667B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE consults ADD CONSTRAINT FK_55474E961EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE follows ADD CONSTRAINT FK_4B638A73506DF1E3 FOREIGN KEY (users_source) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE follows ADD CONSTRAINT FK_4B638A734988A16C FOREIGN KEY (users_target) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE set_ ADD CONSTRAINT FK_A655293267B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE set_ ADD CONSTRAINT FK_A65529327CCD6FB7 FOREIGN KEY (preferences_id) REFERENCES preferences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE has ADD CONSTRAINT FK_C6F39EA67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE has ADD CONSTRAINT FK_C6F39EA9A0035F3 FOREIGN KEY (ranks_id) REFERENCES ranks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suscribed ADD CONSTRAINT FK_444DEDC367B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suscribed ADD CONSTRAINT FK_444DEDC3A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168A76ED395');
        $this->addSql('ALTER TABLE mentions DROP FOREIGN KEY FK_FE39735F1EBAF6CC');
        $this->addSql('ALTER TABLE mentions DROP FOREIGN KEY FK_FE39735F67B3B43D');
        $this->addSql('ALTER TABLE includes DROP FOREIGN KEY FK_611438CF1EBAF6CC');
        $this->addSql('ALTER TABLE includes DROP FOREIGN KEY FK_611438CFA21214B7');
        $this->addSql('ALTER TABLE concerns DROP FOREIGN KEY FK_C32BFA2A1EBAF6CC');
        $this->addSql('ALTER TABLE concerns DROP FOREIGN KEY FK_C32BFA2A8D7B4FB4');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF3466856992D9');
        $this->addSql('ALTER TABLE opinions DROP FOREIGN KEY FK_BEAF78D0A76ED395');
        $this->addSql('ALTER TABLE opinions DROP FOREIGN KEY FK_BEAF78D07294869C');
        $this->addSql('ALTER TABLE socials DROP FOREIGN KEY FK_68A3B869A76ED395');
        $this->addSql('ALTER TABLE consults DROP FOREIGN KEY FK_55474E9667B3B43D');
        $this->addSql('ALTER TABLE consults DROP FOREIGN KEY FK_55474E961EBAF6CC');
        $this->addSql('ALTER TABLE follows DROP FOREIGN KEY FK_4B638A73506DF1E3');
        $this->addSql('ALTER TABLE follows DROP FOREIGN KEY FK_4B638A734988A16C');
        $this->addSql('ALTER TABLE set_ DROP FOREIGN KEY FK_A655293267B3B43D');
        $this->addSql('ALTER TABLE set_ DROP FOREIGN KEY FK_A65529327CCD6FB7');
        $this->addSql('ALTER TABLE has DROP FOREIGN KEY FK_C6F39EA67B3B43D');
        $this->addSql('ALTER TABLE has DROP FOREIGN KEY FK_C6F39EA9A0035F3');
        $this->addSql('ALTER TABLE suscribed DROP FOREIGN KEY FK_444DEDC367B3B43D');
        $this->addSql('ALTER TABLE suscribed DROP FOREIGN KEY FK_444DEDC3A21214B7');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE mentions');
        $this->addSql('DROP TABLE includes');
        $this->addSql('DROP TABLE concerns');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE opinions');
        $this->addSql('DROP TABLE preferences');
        $this->addSql('DROP TABLE ranks');
        $this->addSql('DROP TABLE socials');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE consults');
        $this->addSql('DROP TABLE follows');
        $this->addSql('DROP TABLE set_');
        $this->addSql('DROP TABLE has');
        $this->addSql('DROP TABLE suscribed');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
