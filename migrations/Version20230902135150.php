<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230902135150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comments_users (comments_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_5CB5428F63379586 (comments_id), INDEX IDX_5CB5428F67B3B43D (users_id), PRIMARY KEY(comments_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments_users ADD CONSTRAINT FK_5CB5428F63379586 FOREIGN KEY (comments_id) REFERENCES comments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments_users ADD CONSTRAINT FK_5CB5428F67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments_users DROP FOREIGN KEY FK_5CB5428F63379586');
        $this->addSql('ALTER TABLE comments_users DROP FOREIGN KEY FK_5CB5428F67B3B43D');
        $this->addSql('DROP TABLE comments_users');
    }
}
