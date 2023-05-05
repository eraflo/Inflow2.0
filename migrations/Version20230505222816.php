<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505222816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE includes (articles_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_611438CF1EBAF6CC (articles_id), INDEX IDX_611438CFA21214B7 (categories_id), PRIMARY KEY(articles_id, categories_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE concerns (articles_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_C32BFA2A1EBAF6CC (articles_id), INDEX IDX_C32BFA2A8D7B4FB4 (tags_id), PRIMARY KEY(articles_id, tags_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE follows (users_source INT NOT NULL, users_target INT NOT NULL, INDEX IDX_4B638A73506DF1E3 (users_source), INDEX IDX_4B638A734988A16C (users_target), PRIMARY KEY(users_source, users_target)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE set_ (users_id INT NOT NULL, preferences_id INT NOT NULL, INDEX IDX_A655293267B3B43D (users_id), INDEX IDX_A65529327CCD6FB7 (preferences_id), PRIMARY KEY(users_id, preferences_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE has (users_id INT NOT NULL, ranks_id INT NOT NULL, INDEX IDX_C6F39EA67B3B43D (users_id), INDEX IDX_C6F39EA9A0035F3 (ranks_id), PRIMARY KEY(users_id, ranks_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suscribed (users_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_444DEDC367B3B43D (users_id), INDEX IDX_444DEDC3A21214B7 (categories_id), PRIMARY KEY(users_id, categories_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE includes ADD CONSTRAINT FK_611438CF1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE includes ADD CONSTRAINT FK_611438CFA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE concerns ADD CONSTRAINT FK_C32BFA2A1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE concerns ADD CONSTRAINT FK_C32BFA2A8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE follows ADD CONSTRAINT FK_4B638A73506DF1E3 FOREIGN KEY (users_source) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE follows ADD CONSTRAINT FK_4B638A734988A16C FOREIGN KEY (users_target) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE set_ ADD CONSTRAINT FK_A655293267B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE set_ ADD CONSTRAINT FK_A65529327CCD6FB7 FOREIGN KEY (preferences_id) REFERENCES preferences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE has ADD CONSTRAINT FK_C6F39EA67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE has ADD CONSTRAINT FK_C6F39EA9A0035F3 FOREIGN KEY (ranks_id) REFERENCES ranks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suscribed ADD CONSTRAINT FK_444DEDC367B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE suscribed ADD CONSTRAINT FK_444DEDC3A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_categories DROP FOREIGN KEY FK_DE004A0EA21214B7');
        $this->addSql('ALTER TABLE articles_categories DROP FOREIGN KEY FK_DE004A0E1EBAF6CC');
        $this->addSql('ALTER TABLE articles_tags DROP FOREIGN KEY FK_354053611EBAF6CC');
        $this->addSql('ALTER TABLE articles_tags DROP FOREIGN KEY FK_354053618D7B4FB4');
        $this->addSql('ALTER TABLE users_categories DROP FOREIGN KEY FK_ED98E9FCA21214B7');
        $this->addSql('ALTER TABLE users_categories DROP FOREIGN KEY FK_ED98E9FC67B3B43D');
        $this->addSql('ALTER TABLE users_preferences DROP FOREIGN KEY FK_1E849A0767B3B43D');
        $this->addSql('ALTER TABLE users_preferences DROP FOREIGN KEY FK_1E849A077CCD6FB7');
        $this->addSql('ALTER TABLE users_ranks DROP FOREIGN KEY FK_2C91045D67B3B43D');
        $this->addSql('ALTER TABLE users_ranks DROP FOREIGN KEY FK_2C91045D9A0035F3');
        $this->addSql('ALTER TABLE users_users DROP FOREIGN KEY FK_F3F401A0506DF1E3');
        $this->addSql('ALTER TABLE users_users DROP FOREIGN KEY FK_F3F401A04988A16C');
        $this->addSql('DROP TABLE articles_categories');
        $this->addSql('DROP TABLE articles_tags');
        $this->addSql('DROP TABLE users_categories');
        $this->addSql('DROP TABLE users_preferences');
        $this->addSql('DROP TABLE users_ranks');
        $this->addSql('DROP TABLE users_users');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles_categories (articles_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_DE004A0E1EBAF6CC (articles_id), INDEX IDX_DE004A0EA21214B7 (categories_id), PRIMARY KEY(articles_id, categories_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE articles_tags (articles_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_354053611EBAF6CC (articles_id), INDEX IDX_354053618D7B4FB4 (tags_id), PRIMARY KEY(articles_id, tags_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users_categories (users_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_ED98E9FC67B3B43D (users_id), INDEX IDX_ED98E9FCA21214B7 (categories_id), PRIMARY KEY(users_id, categories_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users_preferences (users_id INT NOT NULL, preferences_id INT NOT NULL, INDEX IDX_1E849A0767B3B43D (users_id), INDEX IDX_1E849A077CCD6FB7 (preferences_id), PRIMARY KEY(users_id, preferences_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users_ranks (users_id INT NOT NULL, ranks_id INT NOT NULL, INDEX IDX_2C91045D67B3B43D (users_id), INDEX IDX_2C91045D9A0035F3 (ranks_id), PRIMARY KEY(users_id, ranks_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users_users (users_source INT NOT NULL, users_target INT NOT NULL, INDEX IDX_F3F401A04988A16C (users_target), INDEX IDX_F3F401A0506DF1E3 (users_source), PRIMARY KEY(users_source, users_target)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE articles_categories ADD CONSTRAINT FK_DE004A0EA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_categories ADD CONSTRAINT FK_DE004A0E1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_tags ADD CONSTRAINT FK_354053611EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_tags ADD CONSTRAINT FK_354053618D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_categories ADD CONSTRAINT FK_ED98E9FCA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_categories ADD CONSTRAINT FK_ED98E9FC67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_preferences ADD CONSTRAINT FK_1E849A0767B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_preferences ADD CONSTRAINT FK_1E849A077CCD6FB7 FOREIGN KEY (preferences_id) REFERENCES preferences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_ranks ADD CONSTRAINT FK_2C91045D67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_ranks ADD CONSTRAINT FK_2C91045D9A0035F3 FOREIGN KEY (ranks_id) REFERENCES ranks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_users ADD CONSTRAINT FK_F3F401A0506DF1E3 FOREIGN KEY (users_source) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_users ADD CONSTRAINT FK_F3F401A04988A16C FOREIGN KEY (users_target) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE includes DROP FOREIGN KEY FK_611438CF1EBAF6CC');
        $this->addSql('ALTER TABLE includes DROP FOREIGN KEY FK_611438CFA21214B7');
        $this->addSql('ALTER TABLE concerns DROP FOREIGN KEY FK_C32BFA2A1EBAF6CC');
        $this->addSql('ALTER TABLE concerns DROP FOREIGN KEY FK_C32BFA2A8D7B4FB4');
        $this->addSql('ALTER TABLE follows DROP FOREIGN KEY FK_4B638A73506DF1E3');
        $this->addSql('ALTER TABLE follows DROP FOREIGN KEY FK_4B638A734988A16C');
        $this->addSql('ALTER TABLE set_ DROP FOREIGN KEY FK_A655293267B3B43D');
        $this->addSql('ALTER TABLE set_ DROP FOREIGN KEY FK_A65529327CCD6FB7');
        $this->addSql('ALTER TABLE has DROP FOREIGN KEY FK_C6F39EA67B3B43D');
        $this->addSql('ALTER TABLE has DROP FOREIGN KEY FK_C6F39EA9A0035F3');
        $this->addSql('ALTER TABLE suscribed DROP FOREIGN KEY FK_444DEDC367B3B43D');
        $this->addSql('ALTER TABLE suscribed DROP FOREIGN KEY FK_444DEDC3A21214B7');
        $this->addSql('DROP TABLE includes');
        $this->addSql('DROP TABLE concerns');
        $this->addSql('DROP TABLE follows');
        $this->addSql('DROP TABLE set_');
        $this->addSql('DROP TABLE has');
        $this->addSql('DROP TABLE suscribed');
    }
}
