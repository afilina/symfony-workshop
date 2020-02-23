<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200219174350 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add price column.';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->getTable('products');
        $table->addColumn('price', 'integer')
            ->setNotnull(true);
    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable('products');
        $table->dropColumn('price');
    }
}
