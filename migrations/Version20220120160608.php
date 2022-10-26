<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120160608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carte (id INT AUTO_INCREMENT NOT NULL, classe_id INT NOT NULL, nom VARCHAR(80) NOT NULL, image_recto VARCHAR(100) NOT NULL, attaque SMALLINT NOT NULL, defense SMALLINT NOT NULL, description VARCHAR(160) NOT NULL, prix INT NOT NULL, INDEX IDX_BAD4FFFD8F5EA509 (classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compose (id INT AUTO_INCREMENT NOT NULL, deck_id INT DEFAULT NULL, carte_id INT NOT NULL, nbexemplaires INT NOT NULL, INDEX IDX_AE4C1416111948DC (deck_id), INDEX IDX_AE4C1416C9C7CEB6 (carte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE deck (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_4FAC3637A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joue (id INT AUTO_INCREMENT NOT NULL, users_id INT DEFAULT NULL, partie_id INT DEFAULT NULL, INDEX IDX_59C45C0267B3B43D (users_id), INDEX IDX_59C45C02E075F7A4 (partie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partie (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE possede (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, carte_id INT NOT NULL, nbexemplaires INT NOT NULL, INDEX IDX_3D0B1508A76ED395 (user_id), INDEX IDX_3D0B1508C9C7CEB6 (carte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, argent INT NOT NULL, xp INT NOT NULL, avatar VARCHAR(255) NOT NULL, date_anniversaire DATE DEFAULT NULL, pseudo VARCHAR(50) NOT NULL, is_verified TINYINT(1) DEFAULT NULL, niveau INT NOT NULL, bio LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64986CC499D (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carte ADD CONSTRAINT FK_BAD4FFFD8F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
        $this->addSql('ALTER TABLE compose ADD CONSTRAINT FK_AE4C1416111948DC FOREIGN KEY (deck_id) REFERENCES deck (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE compose ADD CONSTRAINT FK_AE4C1416C9C7CEB6 FOREIGN KEY (carte_id) REFERENCES carte (id)');
        $this->addSql('ALTER TABLE deck ADD CONSTRAINT FK_4FAC3637A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE joue ADD CONSTRAINT FK_59C45C0267B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE joue ADD CONSTRAINT FK_59C45C02E075F7A4 FOREIGN KEY (partie_id) REFERENCES partie (id)');
        $this->addSql('ALTER TABLE possede ADD CONSTRAINT FK_3D0B1508A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE possede ADD CONSTRAINT FK_3D0B1508C9C7CEB6 FOREIGN KEY (carte_id) REFERENCES carte (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compose DROP FOREIGN KEY FK_AE4C1416C9C7CEB6');
        $this->addSql('ALTER TABLE possede DROP FOREIGN KEY FK_3D0B1508C9C7CEB6');
        $this->addSql('ALTER TABLE carte DROP FOREIGN KEY FK_BAD4FFFD8F5EA509');
        $this->addSql('ALTER TABLE compose DROP FOREIGN KEY FK_AE4C1416111948DC');
        $this->addSql('ALTER TABLE joue DROP FOREIGN KEY FK_59C45C02E075F7A4');
        $this->addSql('ALTER TABLE deck DROP FOREIGN KEY FK_4FAC3637A76ED395');
        $this->addSql('ALTER TABLE joue DROP FOREIGN KEY FK_59C45C0267B3B43D');
        $this->addSql('ALTER TABLE possede DROP FOREIGN KEY FK_3D0B1508A76ED395');
        $this->addSql('DROP TABLE carte');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP TABLE compose');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP TABLE joue');
        $this->addSql('DROP TABLE partie');
        $this->addSql('DROP TABLE possede');
        $this->addSql('DROP TABLE user');
    }
}
