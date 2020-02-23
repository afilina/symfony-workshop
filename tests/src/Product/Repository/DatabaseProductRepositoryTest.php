<?php
declare(strict_types=1);

namespace App\Tests\Product\Repository;

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
        $this->database->insert('products', ['name' => 'Product 1']);
        $this->database->insert('products', ['name' => 'Product 2']);
        self::assertEquals(
            [
                ['name' => 'Product 1'],
                ['name' => 'Product 2']
            ],
            $this->repository->getListData()
        );
    }
}
