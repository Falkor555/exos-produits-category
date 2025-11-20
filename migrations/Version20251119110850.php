<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251119110850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderateur (id INT NOT NULL, category_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DDC355412469DE2 ON moderateur (category_id)');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE moderateur ADD CONSTRAINT FK_6DDC355412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE moderateur ADD CONSTRAINT FK_6DDC3554BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE utilisateur DROP CONSTRAINT fk_1d1c63b312469de2');
        $this->addSql('DROP INDEX idx_1d1c63b312469de2');
        $this->addSql('ALTER TABLE utilisateur DROP category_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE admin DROP CONSTRAINT FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE moderateur DROP CONSTRAINT FK_6DDC355412469DE2');
        $this->addSql('ALTER TABLE moderateur DROP CONSTRAINT FK_6DDC3554BF396750');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE moderateur');
        $this->addSql('ALTER TABLE utilisateur ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT fk_1d1c63b312469de2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1d1c63b312469de2 ON utilisateur (category_id)');
    }
}
