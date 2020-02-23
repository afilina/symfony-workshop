<?php
declare(strict_types=1);

namespace App\ShoppingCart\Value;

use Assert\Assert;

final class Quantity
{
    private int $quantity;

    private function __construct(int $quantity)
    {
        Assert::that($quantity)
            ->greaterOrEqualThan(0);

        $this->quantity = $quantity;
    }

    public static function fromInteger(int $quantity): self
    {
        return new self($quantity);
    }

    public function toInteger(): int
    {
        return $this->quantity;
    }
}
