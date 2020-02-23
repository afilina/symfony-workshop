<?php
declare(strict_types=1);

namespace App\Product\Exception;

use App\Product\Value\ProductCode;
use RuntimeException;

final class ProductNotFound extends RuntimeException
{
    private ProductCode $productCode;

    public static function create(ProductCode $productCode): self
    {
        $instance = new self(sprintf('Product with code %s not found', $productCode));
        $instance->productCode = $productCode;
        return $instance;
    }

    public function getProductCode(): ProductCode
    {
        return $this->productCode;
    }
}
