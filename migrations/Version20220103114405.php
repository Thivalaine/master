<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220103114405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE residence ADD representative_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE residence ADD CONSTRAINT FK_3275823C01675FE FOREIGN KEY (representative_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3275823C01675FE ON residence (representative_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE residence DROP FOREIGN KEY FK_3275823C01675FE');
        $this->addSql('DROP INDEX IDX_3275823C01675FE ON residence');
        $this->addSql('ALTER TABLE residence DROP representative_id_id');
    }
}
