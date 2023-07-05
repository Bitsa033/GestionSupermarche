<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230705233225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, qte BIGINT NOT NULL, prix_total BIGINT NOT NULL, dateachat DATETIME NOT NULL, INDEX IDX_26A98456F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE capacite_magasin (id INT AUTO_INCREMENT NOT NULL, magasin_id INT NOT NULL, capacite_actuel INT NOT NULL, INDEX IDX_E63BCACA20096AE3 (magasin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, nomfam VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2473F21352ADF05A (nomfam), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE magasin (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, capacite INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE margeprix (id INT AUTO_INCREMENT NOT NULL, marge BIGINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, famille_id INT NOT NULL, unite_achat_id INT NOT NULL, unite_vente_id INT NOT NULL, nom VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, prix_achat DOUBLE PRECISION NOT NULL, prix_vente DOUBLE PRECISION NOT NULL, qte_en_stock BIGINT DEFAULT NULL, statut VARCHAR(5) NOT NULL, UNIQUE INDEX UNIQ_29A5EC276C6E55B5 (nom), UNIQUE INDEX UNIQ_29A5EC2777153098 (code), INDEX IDX_29A5EC2797A77B84 (famille_id), INDEX IDX_29A5EC27B1F04A04 (unite_achat_id), INDEX IDX_29A5EC2732A28C19 (unite_vente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reception (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, magasin_id INT NOT NULL, date_reception DATETIME NOT NULL, prix_total DOUBLE PRECISION NOT NULL, qte_unit_val BIGINT NOT NULL, qte_tot_val BIGINT NOT NULL, prix_tot_val BIGINT NOT NULL, qte_rec BIGINT NOT NULL, INDEX IDX_50D6852F82EA2E54 (commande_id), INDEX IDX_50D6852F20096AE3 (magasin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sortie_stock (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, qte_sortie BIGINT NOT NULL, valeur DOUBLE PRECISION NOT NULL, profit_unitaire BIGINT NOT NULL, profit_total BIGINT NOT NULL, date_sortie DATETIME NOT NULL, INDEX IDX_D238F364F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, qte_tot BIGINT NOT NULL, prix_total BIGINT NOT NULL, INDEX IDX_4B365660F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uval (id INT AUTO_INCREMENT NOT NULL, nomuval VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_153BECC5EA9AD56C (nomuval), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE capacite_magasin ADD CONSTRAINT FK_E63BCACA20096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2797A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27B1F04A04 FOREIGN KEY (unite_achat_id) REFERENCES uval (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2732A28C19 FOREIGN KEY (unite_vente_id) REFERENCES uval (id)');
        $this->addSql('ALTER TABLE reception ADD CONSTRAINT FK_50D6852F82EA2E54 FOREIGN KEY (commande_id) REFERENCES achat (id)');
        $this->addSql('ALTER TABLE reception ADD CONSTRAINT FK_50D6852F20096AE3 FOREIGN KEY (magasin_id) REFERENCES magasin (id)');
        $this->addSql('ALTER TABLE sortie_stock ADD CONSTRAINT FK_D238F364F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456F347EFB');
        $this->addSql('ALTER TABLE capacite_magasin DROP FOREIGN KEY FK_E63BCACA20096AE3');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2797A77B84');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27B1F04A04');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2732A28C19');
        $this->addSql('ALTER TABLE reception DROP FOREIGN KEY FK_50D6852F82EA2E54');
        $this->addSql('ALTER TABLE reception DROP FOREIGN KEY FK_50D6852F20096AE3');
        $this->addSql('ALTER TABLE sortie_stock DROP FOREIGN KEY FK_D238F364F347EFB');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660F347EFB');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE capacite_magasin');
        $this->addSql('DROP TABLE famille');
        $this->addSql('DROP TABLE magasin');
        $this->addSql('DROP TABLE margeprix');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE reception');
        $this->addSql('DROP TABLE sortie_stock');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE uval');
    }
}
