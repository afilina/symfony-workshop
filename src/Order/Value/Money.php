<?php
declare(strict_types=1);

namespace App\Order\Value;

use Assert\Assert;

final class Money
{
    private int $amount;
    private string $currency;

    private function __construct(int $amount, string $currency)
    {
        Assert::that($amount)
            ->greaterThan(0);

        Assert::that($currency)
            ->length(3);

        $this->amount = $amount;
        $this->currency = $currency;
    }

    public static function fromAmountAndCurrency(int $amount, string $currency): self
    {
        return new self($amount, $currency);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
