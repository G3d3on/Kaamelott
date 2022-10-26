<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211213151529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carte (id INT AUTO_INCREMENT NOT NULL, classes_id INT NOT NULL, nom VARCHAR(80) NOT NULL, image_recto VARCHAR(100) NOT NULL, image_verso VARCHAR(100) NOT NULL, attaque SMALLINT NOT NULL, defense SMALLINT NOT NULL, description VARCHAR(150) NOT NULL, INDEX IDX_BAD4FFFD9E225B24 (classes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compose (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compose_deck (compose_id INT NOT NULL, deck_id INT NOT NULL, INDEX IDX_6B5601052F2EC0B2 (compose_id), INDEX IDX_6B560105111948DC (deck_id), PRIMARY KEY(compose_id, deck_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compose_carte (compose_id INT NOT NULL, carte_id INT NOT NULL, INDEX IDX_7227544A2F2EC0B2 (compose_id), INDEX IDX_7227544AC9C7CEB6 (carte_id), PRIMARY KEY(compose_id, carte_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deck (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carte ADD CONSTRAINT FK_BAD4FFFD9E225B24 FOREIGN KEY (classes_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE compose_deck ADD CONSTRAINT FK_6B5601052F2EC0B2 FOREIGN KEY (compose_id) REFERENCES compose (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compose_deck ADD CONSTRAINT FK_6B560105111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compose_carte ADD CONSTRAINT FK_7227544A2F2EC0B2 FOREIGN KEY (compose_id) REFERENCES compose (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compose_carte ADD CONSTRAINT FK_7227544AC9C7CEB6 FOREIGN KEY (carte_id) REFERENCES carte (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compose_carte DROP FOREIGN KEY FK_7227544AC9C7CEB6');
        $this->addSql('ALTER TABLE carte DROP FOREIGN KEY FK_BAD4FFFD9E225B24');
        $this->addSql('ALTER TABLE compose_deck DROP FOREIGN KEY FK_6B5601052F2EC0B2');
        $this->addSql('ALTER TABLE compose_carte DROP FOREIGN KEY FK_7227544A2F2EC0B2');
        $this->addSql('ALTER TABLE compose_deck DROP FOREIGN KEY FK_6B560105111948DC');
        $this->addSql('DROP TABLE carte');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE compose');
        $this->addSql('DROP TABLE compose_deck');
        $this->addSql('DROP TABLE compose_carte');
        $this->addSql('DROP TABLE deck');
    }
}
