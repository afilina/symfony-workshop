<?php
declare(strict_types=1);

namespace App\Order\Value;

use Assert\Assert;

final class AuthorizationNumber
{
    private string $authorizationNumber;

    private function __construct(string $authorizationNumber)
    {
        Assert::that($authorizationNumber)
            ->length(15);

        $this->authorizationNumber = $authorizationNumber;
    }

    public static function fromString(string $authorizationNumber): self
    {
        return new self($authorizationNumber);
    }

    public function __toString(): string
    {
        return $this->authorizationNumber;
    }
}
