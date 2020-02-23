<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200219230219 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add code column';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->getTable('products');
        $table->addColumn('code', 'string')
            ->setNotnull(true)
            ->setLength(6)
            ->setFixed(true);
    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable('products');
        $table->dropColumn('code');
    }
}
