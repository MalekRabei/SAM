<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203020431 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autorisation (id INT AUTO_INCREMENT NOT NULL, motif VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, nb_heure INT NOT NULL, date_autorisation DATETIME NOT NULL, idEmployee INT DEFAULT NULL, INDEX IDX_9A43134E3C5FFA (idEmployee), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calcul (id INT AUTO_INCREMENT NOT NULL, total INT NOT NULL, absent INT NOT NULL, present INT NOT NULL, en_conge INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conge (id INT AUTO_INCREMENT NOT NULL, motif VARCHAR(255) NOT NULL, date_debut DATETIME NOT NULL, date_fin VARCHAR(255) NOT NULL, nb_jours INT NOT NULL, etat VARCHAR(255) NOT NULL, idEmployee INT DEFAULT NULL, INDEX IDX_2ED89348E3C5FFA (idEmployee), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, societe VARCHAR(255) NOT NULL, poste VARCHAR(255) NOT NULL, matricule VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, t VARCHAR(255) NOT NULL, presence VARCHAR(255) NOT NULL, idPoste INT DEFAULT NULL, INDEX IDX_5D9F75A16B4D4CB6 (idPoste), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE perse (id INT AUTO_INCREMENT NOT NULL, num_perse VARCHAR(255) NOT NULL, note VARCHAR(255) NOT NULL, date_perse DATETIME NOT NULL, idEmployee INT DEFAULT NULL, INDEX IDX_5DB7133FE3C5FFA (idEmployee), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, poste VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE presence (id INT AUTO_INCREMENT NOT NULL, present VARCHAR(255) NOT NULL, retard VARCHAR(255) NOT NULL, date_presence DATETIME NOT NULL, date_retard DATETIME NOT NULL, idEmployee INT DEFAULT NULL, INDEX IDX_6977C7A5E3C5FFA (idEmployee), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE autorisation ADD CONSTRAINT FK_9A43134E3C5FFA FOREIGN KEY (idEmployee) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED89348E3C5FFA FOREIGN KEY (idEmployee) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A16B4D4CB6 FOREIGN KEY (idPoste) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE perse ADD CONSTRAINT FK_5DB7133FE3C5FFA FOREIGN KEY (idEmployee) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE presence ADD CONSTRAINT FK_6977C7A5E3C5FFA FOREIGN KEY (idEmployee) REFERENCES employee (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE autorisation DROP FOREIGN KEY FK_9A43134E3C5FFA');
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED89348E3C5FFA');
        $this->addSql('ALTER TABLE perse DROP FOREIGN KEY FK_5DB7133FE3C5FFA');
        $this->addSql('ALTER TABLE presence DROP FOREIGN KEY FK_6977C7A5E3C5FFA');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A16B4D4CB6');
        $this->addSql('DROP TABLE autorisation');
        $this->addSql('DROP TABLE calcul');
        $this->addSql('DROP TABLE conge');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE perse');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE presence');
    }
}
