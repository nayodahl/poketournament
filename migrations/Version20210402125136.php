<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402125136 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon ADD tree_root INT DEFAULT NULL, ADD parent_id INT DEFAULT NULL, ADD lft INT NOT NULL, ADD lvl INT NOT NULL, ADD rgt INT NOT NULL');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3A977936C FOREIGN KEY (tree_root) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3727ACA70 FOREIGN KEY (parent_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_62DC90F3A977936C ON pokemon (tree_root)');
        $this->addSql('CREATE INDEX IDX_62DC90F3727ACA70 ON pokemon (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3A977936C');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3727ACA70');
        $this->addSql('DROP INDEX IDX_62DC90F3A977936C ON pokemon');
        $this->addSql('DROP INDEX IDX_62DC90F3727ACA70 ON pokemon');
        $this->addSql('ALTER TABLE pokemon DROP tree_root, DROP parent_id, DROP lft, DROP lvl, DROP rgt');
    }
}
