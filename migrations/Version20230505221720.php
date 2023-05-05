<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505221720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_users (users_source INT NOT NULL, users_target INT NOT NULL, INDEX IDX_F3F401A0506DF1E3 (users_source), INDEX IDX_F3F401A04988A16C (users_target), PRIMARY KEY(users_source, users_target)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_preferences (users_id INT NOT NULL, preferences_id INT NOT NULL, INDEX IDX_1E849A0767B3B43D (users_id), INDEX IDX_1E849A077CCD6FB7 (preferences_id), PRIMARY KEY(users_id, preferences_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_ranks (users_id INT NOT NULL, ranks_id INT NOT NULL, INDEX IDX_2C91045D67B3B43D (users_id), INDEX IDX_2C91045D9A0035F3 (ranks_id), PRIMARY KEY(users_id, ranks_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_categories (users_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_ED98E9FC67B3B43D (users_id), INDEX IDX_ED98E9FCA21214B7 (categories_id), PRIMARY KEY(users_id, categories_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_users ADD CONSTRAINT FK_F3F401A0506DF1E3 FOREIGN KEY (users_source) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_users ADD CONSTRAINT FK_F3F401A04988A16C FOREIGN KEY (users_target) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_preferences ADD CONSTRAINT FK_1E849A0767B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_preferences ADD CONSTRAINT FK_1E849A077CCD6FB7 FOREIGN KEY (preferences_id) REFERENCES preferences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_ranks ADD CONSTRAINT FK_2C91045D67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_ranks ADD CONSTRAINT FK_2C91045D9A0035F3 FOREIGN KEY (ranks_id) REFERENCES ranks (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_categories ADD CONSTRAINT FK_ED98E9FC67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_categories ADD CONSTRAINT FK_ED98E9FCA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_users DROP FOREIGN KEY FK_F3F401A0506DF1E3');
        $this->addSql('ALTER TABLE users_users DROP FOREIGN KEY FK_F3F401A04988A16C');
        $this->addSql('ALTER TABLE users_preferences DROP FOREIGN KEY FK_1E849A0767B3B43D');
        $this->addSql('ALTER TABLE users_preferences DROP FOREIGN KEY FK_1E849A077CCD6FB7');
        $this->addSql('ALTER TABLE users_ranks DROP FOREIGN KEY FK_2C91045D67B3B43D');
        $this->addSql('ALTER TABLE users_ranks DROP FOREIGN KEY FK_2C91045D9A0035F3');
        $this->addSql('ALTER TABLE users_categories DROP FOREIGN KEY FK_ED98E9FC67B3B43D');
        $this->addSql('ALTER TABLE users_categories DROP FOREIGN KEY FK_ED98E9FCA21214B7');
        $this->addSql('DROP TABLE users_users');
        $this->addSql('DROP TABLE users_preferences');
        $this->addSql('DROP TABLE users_ranks');
        $this->addSql('DROP TABLE users_categories');
    }
}
