<?php
declare(strict_types=1);

namespace App\Product\Repository;

use App\Entity\Product;
use App\EventListener\UserPreferences;
use App\Product\Exception\DuplicateProduct;
use App\Product\Exception\ProductNotFound;
use App\Product\Value\ProductCode;
use App\Upload\Repository\ImageRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\FetchMode;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class DatabaseProductRepository implements ProductRepository
{
    private Connection $connection;
    private UserPreferences $userPreferences;
    private ImageRepository $imageRepository;
    private CacheInterface $cache;

    public function __construct(
        Connection $connection,
        UserPreferences $userPreferences,
        ImageRepository $imageRepository,
        CacheInterface $cache
    )
    {
        $this->connection = $connection;
        $this->userPreferences = $userPreferences;
        $this->imageRepository = $imageRepository;
        $this->cache = $cache;
    }

    public function getListData(): array
    {
        $rows = $this->cache->get('products.list.all.query', function (ItemInterface $cacheItem) {
//            $cacheItem->expiresAfter(5);
            $statement = $this->connection->prepare(
                'SELECT
                    p.code,
                    IFNULL(t.name, "***MISSING_NAME***") as name,
                    price,
                    coverFileName
                FROM products p
                LEFT JOIN product_translation t ON t.code = p.code AND t.locale = :locale');
            $statement->execute(['locale' => $this->userPreferences->getLocale()]);
            return $statement->fetchAll(FetchMode::ASSOCIATIVE);
        });

        return array_map(function (array $row) {
            return $this->rowToProduct($row);
        }, $rows);
    }

    /**
     * @inheritDoc
     */
    public function getListByCodes(array $codes): array
    {
        $statement = $this->connection->executeQuery(
            'SELECT
                p.code,
                IFNULL(t.name, "***MISSING_NAME***") as name,
                price,
                coverFileName
            FROM products p
            LEFT JOIN product_translation t ON t.code = p.code AND t.locale = :locale
            WHERE p.code IN (:codes)',
            [
                'codes' => $codes,
                'locale' => $this->userPreferences->getLocale()
            ], [
                'codes' => Connection::PARAM_STR_ARRAY
            ]);
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
        $product->coverFileName = $row['coverFileName'];
        $product->coverUrl = $this->imageRepository->getUrlByFileName($product->coverFileName);

        return $product;
    }

    private function productToRow(Product $product): array
    {
        // Column names can differ.
        // Also, can map a single entity to multiple tables or even across databases.
        return [
            'code' => $product->code,
            'price' => $product->price,
            'coverFileName' => $product->coverFileName,
        ];
    }

    public function getByCode(ProductCode $code): Product
    {
        $statement = $this->connection->prepare(
            'SELECT
                p.code,
                IFNULL(t.name, "***MISSING_NAME***") as name,
                price,
                coverFileName
            FROM products p
            LEFT JOIN product_translation t ON t.code = p.code AND t.locale = :locale
            WHERE p.code = :code');
        $statement->execute([
            'code' => $code,
            'locale' => $this->userPreferences->getLocale(),
        ]);
        $row = $statement->fetch(FetchMode::ASSOCIATIVE);

        if ($row === false) {
            throw ProductNotFound::create($code);
        }

        return $this->rowToProduct($row);
    }

    public function create(ProductCode $code, Product $product): void
    {
        $product->coverFileName = $this->imageRepository->create($product->cover);

        try {
            $this->connection->insert('products', $this->productToRow($product));
        } catch (UniqueConstraintViolationException $exception) {
            throw DuplicateProduct::create($code);
        }

        $this->replaceTranslation($product);
    }

    public function update(ProductCode $code, Product $product): void
    {
        if ($product->cover !== null) {
            $product->coverFileName = $this->imageRepository->create($product->cover);
        }
        $this->connection->update('products', $this->productToRow($product), ['code' => $code]);
        $this->replaceTranslation($product);
    }

    protected function replaceTranslation(Product $product): void
    {
        $query = $this->connection->createQueryBuilder()
            ->insert('product_translation')
            ->values([
                'name' => ':name',
                'code' => ':code',
                'locale' => ':locale'
            ])->setParameters([
                'name' => $product->name,
                'code' => $product->code,
                'locale' => $this->userPreferences->getLocale()
            ]);

        $this->connection->executeQuery(
            str_replace('INSERT INTO', 'REPLACE INTO', $query->getSQL()),
            $query->getParameters(),
            $query->getParameterTypes()
        );
    }
}
