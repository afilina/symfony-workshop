<?php
declare(strict_types=1);

namespace App\Product\Repository;

use App\Entity\Product;

interface ProductRepository
{
    /**
     * @return Product[]
     */
    public function getListData(): array;
}
