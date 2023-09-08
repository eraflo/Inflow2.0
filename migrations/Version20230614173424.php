<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230614173424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments ADD author_id INT NOT NULL, ADD from_article_id INT NOT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A5930409B FOREIGN KEY (from_article_id) REFERENCES articles (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AF675F31B ON comments (author_id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A5930409B ON comments (from_article_id)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E963379586');
        $this->addSql('DROP INDEX IDX_1483A5E963379586 ON users');
        $this->addSql('ALTER TABLE users DROP comments_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AF675F31B');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A5930409B');
        $this->addSql('DROP INDEX IDX_5F9E962AF675F31B ON comments');
        $this->addSql('DROP INDEX IDX_5F9E962A5930409B ON comments');
        $this->addSql('ALTER TABLE comments DROP author_id, DROP from_article_id');
        $this->addSql('ALTER TABLE users ADD comments_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E963379586 FOREIGN KEY (comments_id) REFERENCES comments (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E963379586 ON users (comments_id)');
    }
}
