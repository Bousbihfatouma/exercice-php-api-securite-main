<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240927211320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, societe_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_50159CA9FCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE societe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, numero_siret VARCHAR(14) NOT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE societe_user (societe_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EFBCEA58FCF77503 (societe_id), INDEX IDX_EFBCEA58A76ED395 (user_id), PRIMARY KEY(societe_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_projet (user_id INT NOT NULL, projet_id INT NOT NULL, INDEX IDX_35478794A76ED395 (user_id), INDEX IDX_35478794C18272 (projet_id), PRIMARY KEY(user_id, projet_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
        $this->addSql('ALTER TABLE societe_user ADD CONSTRAINT FK_EFBCEA58FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE societe_user ADD CONSTRAINT FK_EFBCEA58A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_projet ADD CONSTRAINT FK_35478794A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_projet ADD CONSTRAINT FK_35478794C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, roles JSON NOT NULL, password VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9FCF77503');
        $this->addSql('ALTER TABLE societe_user DROP FOREIGN KEY FK_EFBCEA58FCF77503');
        $this->addSql('ALTER TABLE societe_user DROP FOREIGN KEY FK_EFBCEA58A76ED395');
        $this->addSql('ALTER TABLE user_projet DROP FOREIGN KEY FK_35478794A76ED395');
        $this->addSql('ALTER TABLE user_projet DROP FOREIGN KEY FK_35478794C18272');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE societe');
        $this->addSql('DROP TABLE societe_user');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE user_projet');
    }
}
