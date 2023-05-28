<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528081631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reception CHANGE qte qte_rec BIGINT NOT NULL');
        $this->addSql('ALTER TABLE stock DROP date_stockage');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reception CHANGE qte_rec qte BIGINT NOT NULL');
        $this->addSql('ALTER TABLE stock ADD date_stockage DATETIME NOT NULL');
    }
}
