<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230512094400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE capacite_magasin ADD capacite_actuel INT NOT NULL, DROP nb_initial, DROP nb_actuel');
        $this->addSql('ALTER TABLE sortie_stock ADD profit_unitaire BIGINT NOT NULL, ADD profit_total BIGINT NOT NULL');
        $this->addSql('ALTER TABLE stock DROP profit_unitaire, DROP profit_total');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE capacite_magasin ADD nb_actuel INT NOT NULL, CHANGE capacite_actuel nb_initial INT NOT NULL');
        $this->addSql('ALTER TABLE sortie_stock DROP profit_unitaire, DROP profit_total');
        $this->addSql('ALTER TABLE stock ADD profit_unitaire BIGINT NOT NULL, ADD profit_total BIGINT NOT NULL');
    }
}
