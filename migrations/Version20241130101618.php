<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241130101618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adds category, comment, post, user, and messenger_messages tables';
    }

    public function up(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform();

        // Create 'category' table if it does not exist
        if (!$schema->hasTable('category')) {
            $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        // Create 'comment' table if it does not exist
        if (!$schema->hasTable('comment')) {
            $this->addSql('CREATE TABLE comment (id INT NOT NULL, user_c_id INT NOT NULL, commented_post_id INT NOT NULL, content LONGTEXT NOT NULL, timestamp DATETIME NOT NULL, INDEX IDX_9474526CEB56D71A (user_c_id), INDEX IDX_9474526C2BCA0A99 (commented_post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CEB56D71A FOREIGN KEY (user_c_id) REFERENCES `user` (id)');
            $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C2BCA0A99 FOREIGN KEY (commented_post_id) REFERENCES post (id)');
        }

        // Create 'post' table if it does not exist
        if (!$schema->hasTable('post')) {
            $this->addSql('CREATE TABLE post (id INT NOT NULL, user_post_id INT NOT NULL, post_category_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_5A8A6C8D13841D26 (user_post_id), INDEX IDX_5A8A6C8DFE0617CD (post_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D13841D26 FOREIGN KEY (user_post_id) REFERENCES `user` (id)');
            $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DFE0617CD FOREIGN KEY (post_category_id) REFERENCES category (id)');
        }

        // Create 'user' table if it does not exist
        if (!$schema->hasTable('user')) {
            $this->addSql('CREATE TABLE `user` (id INT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        // Create 'messenger_messages' table if it does not exist
        if (!$schema->hasTable('messenger_messages')) {
            $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CEB56D71A');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C2BCA0A99');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D13841D26');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DFE0617CD');
        $this->addSql('DROP TABLE IF EXISTS category');
        $this->addSql('DROP TABLE IF EXISTS comment');
        $this->addSql('DROP TABLE IF EXISTS post');
        $this->addSql('DROP TABLE IF EXISTS `user`');
        $this->addSql('DROP TABLE IF EXISTS messenger_messages');
    }
}
