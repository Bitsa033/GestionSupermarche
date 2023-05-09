<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509175850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE capacite_magasin (id INT AUTO_INCREMENT NOT NULL, nb_initial INT NOT NULL, nb_actuel INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magasin (id INT AUTO_INCREMENT NOT NULL, capacite_magasin_id INT NOT NULL, nom VARCHAR(255) NOT NULL, capacite INT NOT NULL, INDEX IDX_54AF5F277A6700A0 (capacite_magasin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sortie_stock (id INT AUTO_INCREMENT NOT NULL, stock_id INT NOT NULL, qte_sortie BIGINT NOT NULL, valeur DOUBLE PRECISION NOT NULL, date_sortie DATETIME NOT NULL, INDEX IDX_D238F364DCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE magasin ADD CONSTRAINT FK_54AF5F277A6700A0 FOREIGN KEY (capacite_magasin_id) REFERENCES capacite_magasin (id)');
        $this->addSql('ALTER TABLE sortie_stock ADD CONSTRAINT FK_D238F364DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656606BA5E61F');
        $this->addSql('DROP INDEX IDX_4B3656606BA5E61F ON stock');
        $this->addSql('ALTER TABLE stock DROP unite_stockage_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE magasin DROP FOREIGN KEY FK_54AF5F277A6700A0');
        $this->addSql('DROP TABLE capacite_magasin');
        $this->addSql('DROP TABLE magasin');
        $this->addSql('DROP TABLE sortie_stock');
        $this->addSql('ALTER TABLE stock ADD unite_stockage_id INT NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656606BA5E61F FOREIGN KEY (unite_stockage_id) REFERENCES uval (id)');
        $this->addSql('CREATE INDEX IDX_4B3656606BA5E61F ON stock (unite_stockage_id)');
    }
}
