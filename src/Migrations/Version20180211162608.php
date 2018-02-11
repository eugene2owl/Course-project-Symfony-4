<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180211162608 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD firstname VARCHAR(255) NOT NULL, ADD secondname VARCHAR(255) NOT NULL, ADD thirdname VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64983A00E68 ON user (firstname)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649EAB117EF ON user (secondname)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649359E812 ON user (thirdname)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_8D93D64983A00E68 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649EAB117EF ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649359E812 ON user');
        $this->addSql('ALTER TABLE user DROP firstname, DROP secondname, DROP thirdname');
    }
}
