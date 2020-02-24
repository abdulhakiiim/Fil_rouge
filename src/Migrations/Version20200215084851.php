<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200215084851 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF652607B44BAED');
        $this->addSql('DROP INDEX IDX_CFF652607B44BAED ON compte');
        $this->addSql('ALTER TABLE compte CHANGE compt_part_id part_object_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260E3006803 FOREIGN KEY (part_object_id) REFERENCES partenaire (id)');
        $this->addSql('CREATE INDEX IDX_CFF65260E3006803 ON compte (part_object_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260E3006803');
        $this->addSql('DROP INDEX IDX_CFF65260E3006803 ON compte');
        $this->addSql('ALTER TABLE compte CHANGE part_object_id compt_part_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF652607B44BAED FOREIGN KEY (compt_part_id) REFERENCES partenaire (id)');
        $this->addSql('CREATE INDEX IDX_CFF652607B44BAED ON compte (compt_part_id)');
    }
}
