<?php
declare(strict_types=1);

namespace App\Product\Exception;

use App\Product\Value\ProductCode;
use DomainException;

final class DuplicateProduct extends DomainException
{
    private ProductCode $productCode;

    public static function create(ProductCode $productCode): self
    {
        $instance = new self(sprintf('Duplicate product code %s', $productCode));
        $instance->productCode = $productCode;
        return $instance;
    }

    public function getProductCode(): ProductCode
    {
        return $this->productCode;
    }
}
