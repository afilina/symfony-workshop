<?php
declare(strict_types=1);

namespace App\Tests\Product\Repository;

use App\Product\Repository\DatabaseProductRepository;
use App\Tests\RepositoryTestCase;

/** @covers \App\Product\Repository\DatabaseProductRepository */
class DatabaseProductRepositoryTest extends RepositoryTestCase
{
    private DatabaseProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new DatabaseProductRepository();
    }

    public function testGetListData(): void
    {
    }
}
