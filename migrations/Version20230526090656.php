<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526090656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656607C14DF52');
        $this->addSql('DROP INDEX IDX_4B3656607C14DF52 ON stock');
        $this->addSql('ALTER TABLE stock DROP qte_init, CHANGE reception_id produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_4B365660F347EFB ON stock (produit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660F347EFB');
        $this->addSql('DROP INDEX IDX_4B365660F347EFB ON stock');
        $this->addSql('ALTER TABLE stock ADD qte_init BIGINT NOT NULL, CHANGE produit_id reception_id INT NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656607C14DF52 FOREIGN KEY (reception_id) REFERENCES reception (id)');
        $this->addSql('CREATE INDEX IDX_4B3656607C14DF52 ON stock (reception_id)');
    }
}
