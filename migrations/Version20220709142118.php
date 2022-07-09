<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220709142118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660179A8BF2');
        $this->addSql('DROP INDEX IDX_4B365660179A8BF2 ON stock');
        $this->addSql('ALTER TABLE stock ADD qts BIGINT NOT NULL, ADD prixvts BIGINT NOT NULL, ADD prixvus BIGINT NOT NULL, DROP qs, DROP pvts, DROP pvus, DROP qgvs, DROP c, DROP qgu, DROP qtu, DROP puvs, CHANGE us_id unitest_id INT NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656602BDE1C2B FOREIGN KEY (unitest_id) REFERENCES uval (id)');
        $this->addSql('CREATE INDEX IDX_4B3656602BDE1C2B ON stock (unitest_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656602BDE1C2B');
        $this->addSql('DROP INDEX IDX_4B3656602BDE1C2B ON stock');
        $this->addSql('ALTER TABLE stock ADD qs BIGINT NOT NULL, ADD pvts BIGINT NOT NULL, ADD pvus BIGINT NOT NULL, ADD qgvs BIGINT NOT NULL, ADD c VARCHAR(5) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD qgu BIGINT NOT NULL, ADD qtu BIGINT NOT NULL, ADD puvs BIGINT NOT NULL, DROP qts, DROP prixvts, DROP prixvus, CHANGE unitest_id us_id INT NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660179A8BF2 FOREIGN KEY (us_id) REFERENCES uval (id)');
        $this->addSql('CREATE INDEX IDX_4B365660179A8BF2 ON stock (us_id)');
    }
}
