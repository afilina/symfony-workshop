<?php
declare(strict_types=1);

namespace App\Product\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;

final class DatabaseProductRepository implements ProductRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getListData(): array
    {
        $statement = $this->connection->prepare('SELECT name FROM products');
        $statement->execute();
        return $statement->fetchAll(FetchMode::ASSOCIATIVE);
    }
}
