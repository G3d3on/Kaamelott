<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211225145651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compose DROP FOREIGN KEY FK_AE4C1416111948DC');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649111948DC');
        $this->addSql('DROP TABLE compose');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP INDEX IDX_8D93D649111948DC ON user');
        $this->addSql('ALTER TABLE user DROP deck_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compose (id INT AUTO_INCREMENT NOT NULL, carte_id INT DEFAULT NULL, deck_id INT DEFAULT NULL, nb_exemplaires INT DEFAULT NULL, INDEX IDX_AE4C1416111948DC (deck_id), INDEX IDX_AE4C1416C9C7CEB6 (carte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE deck (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE compose ADD CONSTRAINT FK_AE4C1416111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
        $this->addSql('ALTER TABLE compose ADD CONSTRAINT FK_AE4C1416C9C7CEB6 FOREIGN KEY (carte_id) REFERENCES carte (id)');
        $this->addSql('ALTER TABLE user ADD deck_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649111948DC FOREIGN KEY (deck_id) REFERENCES deck (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649111948DC ON user (deck_id)');
    }
}
