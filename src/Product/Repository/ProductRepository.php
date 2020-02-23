<?php
declare(strict_types=1);

namespace App\Product\Repository;

use App\Entity\Product;
use App\Product\Value\ProductCode;

interface ProductRepository
{
    /**
     * @return Product[]
     */
    public function getListData(): array;

    public function getByCode(ProductCode $code): Product;

    public function create(ProductCode $code, Product $product): void;

    public function update(ProductCode $code, Product $product): void;
}
