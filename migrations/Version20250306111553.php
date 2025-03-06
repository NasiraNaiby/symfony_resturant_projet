<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306111553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, commands_id INT DEFAULT NULL, quantite INT NOT NULL, plat_prix INT NOT NULL, totale_prix INT NOT NULL, UNIQUE INDEX UNIQ_24CC0DF2F7982617 (commands_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier_plats (panier_id INT NOT NULL, plats_id INT NOT NULL, INDEX IDX_AC674DB7F77D927C (panier_id), INDEX IDX_AC674DB7AA14E1C8 (plats_id), PRIMARY KEY(panier_id, plats_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2F7982617 FOREIGN KEY (commands_id) REFERENCES commands (id)');
        $this->addSql('ALTER TABLE panier_plats ADD CONSTRAINT FK_AC674DB7F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier_plats ADD CONSTRAINT FK_AC674DB7AA14E1C8 FOREIGN KEY (plats_id) REFERENCES plats (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2F7982617');
        $this->addSql('ALTER TABLE panier_plats DROP FOREIGN KEY FK_AC674DB7F77D927C');
        $this->addSql('ALTER TABLE panier_plats DROP FOREIGN KEY FK_AC674DB7AA14E1C8');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panier_plats');
    }
}
