<?php
declare(strict_types=1);

namespace App\ShoppingCart\Value;

use App\Product\Value\ProductCode;

final class CartItem
{
    private ProductCode $code;
    private Quantity $quantity;

    private function __construct(ProductCode $code, Quantity $quantity)
    {
        $this->code = $code;
        $this->quantity = $quantity;
    }

    public static function fromValues(ProductCode $code, Quantity $quantity): self
    {
        return new self($code, $quantity);
    }

    public function incrementQuantity(Quantity $quantity): self
    {
        return new self(
            $this->code,
            Quantity::fromInteger($this->quantity->toInteger() + $quantity->toInteger())
        );
    }

    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }
}
