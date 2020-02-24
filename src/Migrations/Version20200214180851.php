<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200214180851 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260C645C84A');
        $this->addSql('DROP INDEX IDX_CFF65260C645C84A ON compte');
        $this->addSql('ALTER TABLE compte ADD user_creator VARCHAR(255) NOT NULL, ADD date_creation DATE NOT NULL, CHANGE user_creator_id user_object_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF6526023CDDBCF FOREIGN KEY (user_object_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CFF6526023CDDBCF ON compte (user_object_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF6526023CDDBCF');
        $this->addSql('DROP INDEX IDX_CFF6526023CDDBCF ON compte');
        $this->addSql('ALTER TABLE compte DROP user_creator, DROP date_creation, CHANGE user_object_id user_creator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260C645C84A FOREIGN KEY (user_creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CFF65260C645C84A ON compte (user_creator_id)');
    }
}
