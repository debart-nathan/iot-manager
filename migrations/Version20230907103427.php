<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230907103427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE module (module_id INT AUTO_INCREMENT NOT NULL, module_type_name VARCHAR(255) DEFAULT NULL, status_name VARCHAR(255) DEFAULT NULL, module_name VARCHAR(255) NOT NULL, reference_code VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, activation_date DATETIME NOT NULL, status_message TEXT, INDEX IDX_C242628D34DA4C6 (module_type_name), INDEX IDX_C2426286625D392 (status_name), PRIMARY KEY(module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_type (module_type_name VARCHAR(255) NOT NULL, picture_file VARCHAR(255) NOT NULL, PRIMARY KEY(module_type_name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_type_value (module_type_value_id INT NOT NULL, module_type_name VARCHAR(255) DEFAULT NULL, value_type_name VARCHAR(255) DEFAULT NULL, INDEX IDX_869EF45ED34DA4C6 (module_type_name), INDEX IDX_869EF45ECCBEBEB8 (value_type_name), PRIMARY KEY(module_type_value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (status_name VARCHAR(255) NOT NULL, status_category VARCHAR(255) NOT NULL, PRIMARY KEY(status_name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE value_log (value_log_id INT AUTO_INCREMENT NOT NULL, module_type_value_id INT DEFAULT NULL, module_id INT DEFAULT NULL, data NUMERIC(10, 2) NOT NULL, log_date DATETIME NOT NULL, INDEX IDX_DCCAE22967045CF6 (module_type_value_id), INDEX IDX_DCCAE229AFC2B591 (module_id), PRIMARY KEY(value_log_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE value_type (value_type_name VARCHAR(255) NOT NULL, unit VARCHAR(255) NOT NULL, PRIMARY KEY(value_type_name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628D34DA4C6 FOREIGN KEY (module_type_name) REFERENCES module_type (module_type_name)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C2426286625D392 FOREIGN KEY (status_name) REFERENCES status (status_name)');
        $this->addSql('ALTER TABLE module_type_value ADD CONSTRAINT FK_869EF45ED34DA4C6 FOREIGN KEY (module_type_name) REFERENCES module_type (module_type_name)');
        $this->addSql('ALTER TABLE module_type_value ADD CONSTRAINT FK_869EF45ECCBEBEB8 FOREIGN KEY (value_type_name) REFERENCES value_type (value_type_name)');
        $this->addSql('ALTER TABLE value_log ADD CONSTRAINT FK_DCCAE22967045CF6 FOREIGN KEY (module_type_value_id) REFERENCES module_type_value (module_type_value_id)');
        $this->addSql('ALTER TABLE value_log ADD CONSTRAINT FK_DCCAE229AFC2B591 FOREIGN KEY (module_id) REFERENCES module (module_id)');
        $this->addSql(
            "
    CREATE PROCEDURE check_module_type_consistency(IN module_id INT, IN value_log_id INT)
    BEGIN
        DECLARE module_type_of_module INT;
        DECLARE module_type_of_module_type_value INT;
        
        SELECT m.module_type_id INTO module_type_of_module FROM module AS m WHERE m.module_id = module_id;
        SELECT mt.module_type_id INTO module_type_of_module_type_value FROM module_type_value AS mt WHERE mt.value_log_id = value_log_id;
        
        IF module_type_of_module <> module_type_of_module_type_value THEN
            SIGNAL SQLSTATE '45000';
        END IF;
    END
"
        );
        $this->addSql("
    CREATE TRIGGER enforce_consistency_insert_trigger BEFORE
    INSERT ON value_log FOR EACH ROW
    CALL check_module_type_consistency(NEW.module_id, NEW.value_log_id);
");

        $this->addSql(
            "
    CREATE TRIGGER enforce_consistency_update_trigger BEFORE
    UPDATE ON value_log FOR EACH ROW
    CALL check_module_type_consistency(NEW.module_id, NEW.value_log_id);
"
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP PROCEDURE check_module_type_consistency');
        $this->addSql('DROP TRIGGER enforce_consistency_insert_trigger');
        $this->addSql('DROP TRIGGER enforce_consistency_update_trigger');
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628D34DA4C6');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C2426286625D392');
        $this->addSql('ALTER TABLE module_type_value DROP FOREIGN KEY FK_869EF45ED34DA4C6');
        $this->addSql('ALTER TABLE module_type_value DROP FOREIGN KEY FK_869EF45ECCBEBEB8');
        $this->addSql('ALTER TABLE value_log DROP FOREIGN KEY FK_DCCAE22967045CF6');
        $this->addSql('ALTER TABLE value_log DROP FOREIGN KEY FK_DCCAE229AFC2B591');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE module_type');
        $this->addSql('DROP TABLE module_type_value');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE value_log');
        $this->addSql('DROP TABLE value_type');
    }
}
