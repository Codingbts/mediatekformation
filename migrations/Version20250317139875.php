<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250317139875 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            UPDATE playlist p
            SET formation_nb = (
                SELECT COUNT(f.id)
                FROM formation f
                WHERE f.playlist_id = p.id
            )
        ');
    }

    public function down(Schema $schema): void
    {
         // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE playlist SET formation_nb = 0');
    }
}
