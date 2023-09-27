<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926160520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6A76ED395');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6FD02F13');
        $this->addSql('DROP INDEX IDX_5E90F6D6A76ED395 ON inscription');
        $this->addSql('DROP INDEX IDX_5E90F6D6FD02F13 ON inscription');
        $this->addSql('ALTER TABLE inscription ADD user_id_id INT NOT NULL, ADD evenement_id_id INT NOT NULL, DROP user_id, DROP evenement_id');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D69D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6ECEE32AF FOREIGN KEY (evenement_id_id) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX IDX_5E90F6D69D86650F ON inscription (user_id_id)');
        $this->addSql('CREATE INDEX IDX_5E90F6D6ECEE32AF ON inscription (evenement_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D69D86650F');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6ECEE32AF');
        $this->addSql('DROP INDEX IDX_5E90F6D69D86650F ON inscription');
        $this->addSql('DROP INDEX IDX_5E90F6D6ECEE32AF ON inscription');
        $this->addSql('ALTER TABLE inscription ADD user_id INT NOT NULL, ADD evenement_id INT NOT NULL, DROP user_id_id, DROP evenement_id_id');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5E90F6D6A76ED395 ON inscription (user_id)');
        $this->addSql('CREATE INDEX IDX_5E90F6D6FD02F13 ON inscription (evenement_id)');
    }
}
