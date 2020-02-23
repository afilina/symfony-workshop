<?php
declare(strict_types=1);

namespace App\Product\Repository;

use App\Entity\Product;

final class StubProductRepository implements ProductRepository
{
    /**
     * @inheritDoc
     */
    public function getListData(): array
    {
        $product1 = new Product();
        $product1->name = 'The Outer Worlds';

        $product2 = new Product();
        $product2->name = 'Skyrim';

        return [$product1, $product2];
    }
}
