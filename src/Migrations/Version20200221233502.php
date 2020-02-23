<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200221233502 extends AbstractMigration
{
    const FK = 'product_translation_code';

    public function getDescription() : string
    {
        return 'Copy product translations and drop name column';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('
            INSERT INTO product_translation (locale, code, name)
            SELECT "en", code, name FROM products
        ');

        $this->addSql('
            ALTER TABLE products DROP name
        ');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('
            ALTER TABLE products
            ADD name VARCHAR(200) NOT NULL
        ');

        $this->addSql('
            UPDATE products p
            INNER JOIN product_translation t ON t.code = p.code AND t.locale = "en"
            SET p.name = t.name
        ');
    }
}
