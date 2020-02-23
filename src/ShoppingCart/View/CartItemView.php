<?php
declare(strict_types=1);

namespace App\ShoppingCart\View;

final class CartItemView
{
    private int $quantity;
    private string $name;
    private int $price;

    private function __construct(int $quantity, string $name, int $price)
    {
        $this->quantity = $quantity;
        $this->name = $name;
        $this->price = $price;
    }

    public static function fromValues(int $quantity, string $name, int $price): self
    {
        return new self($quantity, $name, $price);
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): string
    {
        return number_format($this->price / 100, 2);
    }
}
