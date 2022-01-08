<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220106081132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE socialnet (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE socialnet_user (socialnet_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4D924DA6F1B4453 (socialnet_id), INDEX IDX_4D924DAA76ED395 (user_id), PRIMARY KEY(socialnet_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE socialnet_user ADD CONSTRAINT FK_4D924DA6F1B4453 FOREIGN KEY (socialnet_id) REFERENCES socialnet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE socialnet_user ADD CONSTRAINT FK_4D924DAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE socialnet_user DROP FOREIGN KEY FK_4D924DA6F1B4453');
        $this->addSql('DROP TABLE socialnet');
        $this->addSql('DROP TABLE socialnet_user');
    }
}
