<?php
declare(strict_types=1);

namespace Tests\Acceptance\ApplicationState;

interface ApplicationState
{
    public function reset(): void;

    public function addProduct(string $name): void;
}
