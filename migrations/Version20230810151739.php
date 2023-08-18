<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230810151739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscriptions (users_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_4778A0167B3B43D (users_id), INDEX IDX_4778A01A21214B7 (categories_id), PRIMARY KEY(users_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscriptions ADD CONSTRAINT FK_4778A0167B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscriptions ADD CONSTRAINT FK_4778A01A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscribed DROP FOREIGN KEY FK_59D4EE3867B3B43D');
        $this->addSql('ALTER TABLE subscribed DROP FOREIGN KEY FK_59D4EE38A21214B7');
        $this->addSql('DROP TABLE subscribed');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscribed (users_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_59D4EE3867B3B43D (users_id), INDEX IDX_59D4EE38A21214B7 (categories_id), PRIMARY KEY(users_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE subscribed ADD CONSTRAINT FK_59D4EE3867B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscribed ADD CONSTRAINT FK_59D4EE38A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscriptions DROP FOREIGN KEY FK_4778A0167B3B43D');
        $this->addSql('ALTER TABLE subscriptions DROP FOREIGN KEY FK_4778A01A21214B7');
        $this->addSql('DROP TABLE subscriptions');
    }
}
