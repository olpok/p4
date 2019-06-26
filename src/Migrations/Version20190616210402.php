<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190616210402 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE admission (id INT AUTO_INCREMENT NOT NULL, adult INT DEFAULT NULL, child INT DEFAULT NULL, senior INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticket ADD admission_id INT DEFAULT NULL, CHANGE code code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA375C9C554 FOREIGN KEY (admission_id) REFERENCES admission (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA375C9C554 ON ticket (admission_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA375C9C554');
        $this->addSql('DROP TABLE admission');
        $this->addSql('DROP INDEX IDX_97A0ADA375C9C554 ON ticket');
        $this->addSql('ALTER TABLE ticket DROP admission_id, CHANGE code code TINYINT(1) NOT NULL');
    }
}
