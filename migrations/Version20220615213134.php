<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615213134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catuval (id INT AUTO_INCREMENT NOT NULL, nomcatval VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, nom_fam VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, famille_id INT DEFAULT NULL, uvalp_id INT NOT NULL, nom VARCHAR(255) NOT NULL, ref VARCHAR(255) NOT NULL, masse BIGINT NOT NULL, INDEX IDX_29A5EC2797A77B84 (famille_id), INDEX IDX_29A5EC27637F5F81 (uvalp_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, uvalst_id INT NOT NULL, ugv_id INT NOT NULL, qt BIGINT NOT NULL, qs BIGINT NOT NULL, pat BIGINT NOT NULL, pau BIGINT NOT NULL, pvt BIGINT NOT NULL, pvu BIGINT NOT NULL, bvt BIGINT NOT NULL, bvu BIGINT NOT NULL, qgc BIGINT NOT NULL, c VARCHAR(5) NOT NULL, qgv BIGINT NOT NULL, qtv BIGINT NOT NULL, INDEX IDX_4B365660F347EFB (produit_id), INDEX IDX_4B365660A7DA33A5 (uvalst_id), INDEX IDX_4B365660166B75EE (ugv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uval (id INT AUTO_INCREMENT NOT NULL, catuval_id INT NOT NULL, nomuval VARCHAR(255) NOT NULL, INDEX IDX_153BECC595D1C6BC (catuval_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2797A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27637F5F81 FOREIGN KEY (uvalp_id) REFERENCES uval (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660A7DA33A5 FOREIGN KEY (uvalst_id) REFERENCES uval (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660166B75EE FOREIGN KEY (ugv_id) REFERENCES uval (id)');
        $this->addSql('ALTER TABLE uval ADD CONSTRAINT FK_153BECC595D1C6BC FOREIGN KEY (catuval_id) REFERENCES catuval (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE uval DROP FOREIGN KEY FK_153BECC595D1C6BC');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2797A77B84');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660F347EFB');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27637F5F81');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660A7DA33A5');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660166B75EE');
        $this->addSql('DROP TABLE catuval');
        $this->addSql('DROP TABLE famille');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE uval');
    }
}
