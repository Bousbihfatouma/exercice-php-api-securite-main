<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240928161516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_societe_roles (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, societe_id INT NOT NULL, role VARCHAR(50) NOT NULL, INDEX IDX_32628C59A76ED395 (user_id), INDEX IDX_32628C59FCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_societe_roles ADD CONSTRAINT FK_32628C59A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_societe_roles ADD CONSTRAINT FK_32628C59FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_societe_roles DROP FOREIGN KEY FK_32628C59A76ED395');
        $this->addSql('ALTER TABLE user_societe_roles DROP FOREIGN KEY FK_32628C59FCF77503');
        $this->addSql('DROP TABLE user_societe_roles');
    }
}
