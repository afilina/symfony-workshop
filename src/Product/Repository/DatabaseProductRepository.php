<?php
declare(strict_types=1);

namespace App\Product\Repository;

use App\Entity\Product;
use App\Product\Exception\DuplicateProduct;
use App\Product\Exception\DuplicateProductCode;
use App\Product\Exception\ProductNotFound;
use App\Product\Value\ProductCode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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
        $statement = $this->connection->prepare('SELECT * FROM products');
        $statement->execute();
        $rows = $statement->fetchAll(FetchMode::ASSOCIATIVE);

        return array_map(function (array $row) {
            return $this->rowToProduct($row);
        }, $rows);
    }

    private function rowToProduct(array $row): Product
    {
        $product = new Product();
        $product->code = $row['code'];
        $product->name = $row['name'];
        $product->price = (int) $row['price'];

        return $product;
    }

    private function productToRow(Product $product): array
    {
        // Column names can differ.
        // Also, can map a single entity to multiple tables or even across databases.
        return [
            'code' => $product->code,
            'name' => $product->name,
            'price' => $product->price,
        ];
    }

    public function getByCode(ProductCode $code): Product
    {
        $statement = $this->connection->prepare('SELECT * FROM products p WHERE code = :code');
        $statement->execute(['code' => $code,]);
        $row = $statement->fetch(FetchMode::ASSOCIATIVE);

        if ($row === false) {
            throw ProductNotFound::create($code);
        }

        return $this->rowToProduct($row);
    }

    public function create(ProductCode $code, Product $product): void
    {
        try {
            $this->connection->insert('products', $this->productToRow($product));
        } catch (UniqueConstraintViolationException $exception) {
            throw DuplicateProduct::create($code);
        }
    }

    public function update(ProductCode $code, Product $product): void
    {
        $this->connection->update('products', $this->productToRow($product), ['code' => $code]);
    }
}
