<?php
declare(strict_types=1);

namespace App\Tests\Product\Repository;

use App\Entity\Product;
use App\Product\Repository\DatabaseProductRepository;
use App\Tests\RepositoryTestCase;

/** @covers \App\Product\Repository\DatabaseProductRepository */
class DatabaseProductRepositoryTest extends RepositoryTestCase
{
    /** @var DatabaseProductRepository|object|null */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->container->get(DatabaseProductRepository::class);
    }

    public function testGetListData(): void
    {
        $this->database->insert('products', ['code' => '000001'] + $this->getDefaultRow());
        $this->database->insert('product_translation', ['code' => '000001'] + $this->getDefaultTranslation());
        $this->database->insert('products', ['code' => '000002'] + $this->getDefaultRow());
        $this->database->insert('product_translation', ['code' => '000002'] + $this->getDefaultTranslation());

        $product1 = $this->getDefaultProduct();
        $product1->code = '000001';

        $product2 = $this->getDefaultProduct();
        $product2->code = '000002';

        self::assertEquals(
            [$product1, $product2],
            $this->repository->getListData()
        );
    }

    public function getDefaultRow(): array
    {
        return [
            'id' => '1',
            'code' => '000001',
            'price' => 1000,
        ];
    }

    public function getDefaultTranslation(): array
    {
        return [
            'locale' => 'en',
            'code' => '000001',
            'name' => 'Product',
        ];
    }

    public function getDefaultProduct(): Product
    {
        $product = new Product();
        $product->code = '000001';
        $product->name = 'Product';
        $product->price = 1000;

        return $product;
    }
}
