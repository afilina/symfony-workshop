<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200222063826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add products.coverFileName to store image filename';
    }

    public function up(Schema $schema) : void
    {
        // With entropy, uniqid returns 23 chars. Adding buffer for file extension.
        $schema->getTable('products')
            ->addColumn('coverFileName', 'string')
            ->setNotnull(true)
            ->setLength(30);
    }

    public function down(Schema $schema) : void
    {
        $schema->getTable('products')
            ->dropColumn('coverFileName');
    }
}
