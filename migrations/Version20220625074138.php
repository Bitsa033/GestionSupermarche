<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220625074138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, ua_id INT NOT NULL, qa BIGINT NOT NULL, patp BIGINT NOT NULL, paup BIGINT NOT NULL, dateachat DATETIME NOT NULL, INDEX IDX_26A98456F347EFB (produit_id), INDEX IDX_26A98456ED8A14E6 (ua_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, nomfam VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE margeprix (id INT AUTO_INCREMENT NOT NULL, marge BIGINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, famille_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, codep VARCHAR(255) NOT NULL, alerte BIGINT NOT NULL, UNIQUE INDEX UNIQ_29A5EC276C6E55B5 (nom), INDEX IDX_29A5EC2797A77B84 (famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, achat_id INT DEFAULT NULL, us_id INT NOT NULL, uvs_id INT NOT NULL, qs BIGINT NOT NULL, pvts BIGINT NOT NULL, pvus BIGINT NOT NULL, pts BIGINT NOT NULL, pus BIGINT NOT NULL, qgvs BIGINT NOT NULL, c VARCHAR(5) NOT NULL, qgu BIGINT NOT NULL, qtu BIGINT NOT NULL, puvs BIGINT NOT NULL, INDEX IDX_4B365660FE95D117 (achat_id), INDEX IDX_4B365660179A8BF2 (us_id), INDEX IDX_4B3656607C353BEE (uvs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uval (id INT AUTO_INCREMENT NOT NULL, nomuval VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456ED8A14E6 FOREIGN KEY (ua_id) REFERENCES uval (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2797A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660FE95D117 FOREIGN KEY (achat_id) REFERENCES achat (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660179A8BF2 FOREIGN KEY (us_id) REFERENCES uval (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656607C353BEE FOREIGN KEY (uvs_id) REFERENCES uval (id)');
        $this->addSql('INSERT INTO uval(id,nomuval) VALUES(null,"Boite"),(null,"Carton"),
        (null,"Sachet"),(null,"Bouteille"),(null,"Palette"),(null,"Packet"),(null,"Piece")
        ,(null,"KG"),(null,"G"), (null,"Litre")');
        $this->addSql('INSERT INTO margeprix(id,marge) VALUES(null,25),(null,50),
        (null,75),(null,100),(null,125),(null,150),(null,175)
        ,(null,200),(null,225), (null,250)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660FE95D117');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2797A77B84');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456F347EFB');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456ED8A14E6');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660179A8BF2');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656607C353BEE');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE famille');
        $this->addSql('DROP TABLE margeprix');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE uval');
    }
}
