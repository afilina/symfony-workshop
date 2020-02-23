<?php
declare(strict_types=1);

namespace App\Order\Value;

use Assert\Assert;

final class OrderNumber
{
    private string $number;

    private function __construct(string $number)
    {
        Assert::that($number)
            ->length(10);

        $this->number = $number;
    }

    public static function fromString(string $number): self
    {
        return new self($number);
    }

    public function __toString(): string
    {
        return $this->number;
    }
}
