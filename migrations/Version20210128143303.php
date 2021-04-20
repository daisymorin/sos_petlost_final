<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210128143303 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actuality ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE actuality ADD CONSTRAINT FK_4093DDD83DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4093DDD83DA5256D ON actuality (image_id)');
        $this->addSql('ALTER TABLE animals ADD image_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animals ADD CONSTRAINT FK_966C69DD3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE animals ADD CONSTRAINT FK_966C69DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_966C69DD3DA5256D ON animals (image_id)');
        $this->addSql('CREATE INDEX IDX_966C69DDA76ED395 ON animals (user_id)');
        $this->addSql('ALTER TABLE comment ADD animals_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C132B9E58 FOREIGN KEY (animals_id) REFERENCES animals (id)');
        $this->addSql('CREATE INDEX IDX_9474526C132B9E58 ON comment (animals_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actuality DROP FOREIGN KEY FK_4093DDD83DA5256D');
        $this->addSql('DROP INDEX UNIQ_4093DDD83DA5256D ON actuality');
        $this->addSql('ALTER TABLE actuality DROP image_id');
        $this->addSql('ALTER TABLE animals DROP FOREIGN KEY FK_966C69DD3DA5256D');
        $this->addSql('ALTER TABLE animals DROP FOREIGN KEY FK_966C69DDA76ED395');
        $this->addSql('DROP INDEX UNIQ_966C69DD3DA5256D ON animals');
        $this->addSql('DROP INDEX IDX_966C69DDA76ED395 ON animals');
        $this->addSql('ALTER TABLE animals DROP image_id, DROP user_id');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C132B9E58');
        $this->addSql('DROP INDEX IDX_9474526C132B9E58 ON comment');
        $this->addSql('ALTER TABLE comment DROP animals_id');
    }
}
