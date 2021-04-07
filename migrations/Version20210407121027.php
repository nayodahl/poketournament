<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210407121027 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, winner_id INT DEFAULT NULL, loser_id INT DEFAULT NULL, player1_id INT DEFAULT NULL, player2_id INT DEFAULT NULL, tournament_id INT NOT NULL, number INT NOT NULL, score_player1 INT DEFAULT NULL, score_player2 INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_232B318C5DFCD4B8 (winner_id), INDEX IDX_232B318C1BCAA5F6 (loser_id), INDEX IDX_232B318CC0990423 (player1_id), INDEX IDX_232B318CD22CABCD (player2_id), INDEX IDX_232B318C33D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, tree_root INT DEFAULT NULL, parent_id INT DEFAULT NULL, type1_id INT DEFAULT NULL, type2_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, api_id INT NOT NULL, image VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, updated_at DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_62DC90F3A977936C (tree_root), INDEX IDX_62DC90F3727ACA70 (parent_id), INDEX IDX_62DC90F3BFAFA3E1 (type1_id), INDEX IDX_62DC90F3AD1A0C0F (type2_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, date DATETIME DEFAULT NULL, number_pokemons INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournaments_pokemons (tournament_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_94F9BE1233D1A3E7 (tournament_id), INDEX IDX_94F9BE122FE71C3E (pokemon_id), PRIMARY KEY(tournament_id, pokemon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C1BCAA5F6 FOREIGN KEY (loser_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CC0990423 FOREIGN KEY (player1_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CD22CABCD FOREIGN KEY (player2_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3A977936C FOREIGN KEY (tree_root) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3727ACA70 FOREIGN KEY (parent_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3BFAFA3E1 FOREIGN KEY (type1_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3AD1A0C0F FOREIGN KEY (type2_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE tournaments_pokemons ADD CONSTRAINT FK_94F9BE1233D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournaments_pokemons ADD CONSTRAINT FK_94F9BE122FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C5DFCD4B8');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C1BCAA5F6');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CC0990423');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CD22CABCD');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3A977936C');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3727ACA70');
        $this->addSql('ALTER TABLE tournaments_pokemons DROP FOREIGN KEY FK_94F9BE122FE71C3E');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C33D1A3E7');
        $this->addSql('ALTER TABLE tournaments_pokemons DROP FOREIGN KEY FK_94F9BE1233D1A3E7');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3BFAFA3E1');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3AD1A0C0F');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournaments_pokemons');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
    }
}
