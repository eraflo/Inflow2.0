<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230803121515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A7927FBC6');
        $this->addSql('DROP INDEX IDX_5F9E962A7927FBC6 ON comments');
        $this->addSql('ALTER TABLE comments CHANGE replies_id replies_to_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AEE507694 FOREIGN KEY (replies_to_id) REFERENCES comments (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962AEE507694 ON comments (replies_to_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AEE507694');
        $this->addSql('DROP INDEX IDX_5F9E962AEE507694 ON comments');
        $this->addSql('ALTER TABLE comments CHANGE replies_to_id replies_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A7927FBC6 FOREIGN KEY (replies_id) REFERENCES comments (id)');
        $this->addSql('CREATE INDEX IDX_5F9E962A7927FBC6 ON comments (replies_id)');
    }
}
