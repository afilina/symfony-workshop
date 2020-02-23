<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200221233324 extends AbstractMigration
{
    const FK = 'product_translation_code';

    public function getDescription() : string
    {
        return 'Add table for product translations';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('product_translation');
        $table->addColumn('locale', 'string')
            ->setLength(2)
            ->setFixed(true);
        $table->addColumn('code', 'string')
            ->setLength(6)
            ->setFixed(true);
        $table->addColumn('name', 'string')
            ->setLength(200);
        $table->setPrimaryKey(['locale', 'code']);
        $table->addForeignKeyConstraint('products', ['code'], ['code'], [], self::FK);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('product_translation');
    }
}
