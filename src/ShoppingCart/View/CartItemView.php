<?php
declare(strict_types=1);

namespace App\ShoppingCart\View;

final class CartItemView
{
    private int $quantity;
    private string $name;
    private int $price;
    private string $coverUrl;

    private function __construct(int $quantity, string $name, int $price, string $coverUrl)
    {
        $this->quantity = $quantity;
        $this->name = $name;
        $this->price = $price;
        $this->coverUrl = $coverUrl;
    }

    public static function fromValues(int $quantity, string $name, int $price, string $coverUrl): self
    {
        return new self($quantity, $name, $price, $coverUrl);
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

    public function getCoverUrl(): string
    {
        return $this->coverUrl;
    }
}
