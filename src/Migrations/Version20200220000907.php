<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200220000907 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Copy products IDs into the code column and switch to it as the PK';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->getTable('products');
        $this->addSql('UPDATE products SET code = LPAD(id, 6, "0")');
        $table->setPrimaryKey(['code']);
    }

    public function down(Schema $schema) : void
    {
        $table = $schema->getTable('products');
        $table->dropPrimaryKey();
    }
}
