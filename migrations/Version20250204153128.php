<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204153128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE quizz_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quizz (id INT NOT NULL, answered_by_id INT NOT NULL, mistakes INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7C77973D2FC55A77 ON quizz (answered_by_id)');
        $this->addSql('COMMENT ON COLUMN quizz.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE quizz ADD CONSTRAINT FK_7C77973D2FC55A77 FOREIGN KEY (answered_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE quizz_id_seq CASCADE');
        $this->addSql('ALTER TABLE quizz DROP CONSTRAINT FK_7C77973D2FC55A77');
        $this->addSql('DROP TABLE quizz');
    }
}
