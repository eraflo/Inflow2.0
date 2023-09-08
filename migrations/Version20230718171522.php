<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230718171522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opinions ADD comment_id INT DEFAULT NULL, CHANGE article_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opinions ADD CONSTRAINT FK_BEAF78D0F8697D13 FOREIGN KEY (comment_id) REFERENCES comments (id)');
        $this->addSql('CREATE INDEX IDX_BEAF78D0F8697D13 ON opinions (comment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opinions DROP FOREIGN KEY FK_BEAF78D0F8697D13');
        $this->addSql('DROP INDEX IDX_BEAF78D0F8697D13 ON opinions');
        $this->addSql('ALTER TABLE opinions DROP comment_id, CHANGE article_id article_id INT NOT NULL');
    }
}
