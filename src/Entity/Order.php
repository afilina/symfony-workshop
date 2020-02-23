<?php
declare(strict_types=1);

namespace App\Entity;

final class Order
{
    public $number;
    public $items;
    public $shippingAddress;
    public $authorizationNumber;
    public $delivery;

    private $currentPlace = null; // ['paid' => 1];

    public function getCurrentPlace()
    {
        return $this->currentPlace;
    }

    public function setCurrentPlace($currentPlace, $context = []): void
    {
        // Need to persist afterwards
        $this->currentPlace = $currentPlace;
    }

    public function getTotal(): int
    {
        return 2000;
    }
}
