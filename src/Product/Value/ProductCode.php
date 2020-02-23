<?php
declare(strict_types=1);

namespace App\Product\Value;

use Assert\Assert;

final class ProductCode
{
    private string $code;

    private function __construct(string $code)
    {
        Assert::that($code)
            ->notBlank()
            ->numeric()
            ->length(6);

        $this->code = $code;
    }

    public static function fromString(string $code): self
    {
        return new self($code);
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
