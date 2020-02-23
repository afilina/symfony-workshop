<?php
declare(strict_types=1);

namespace App\Order\Repository;

use App\Entity\Order;
use App\Order\Value\OrderNumber;

interface OrderRepository
{
    public function getByNumber(OrderNumber $number): Order;

    public function update(Order $order): void;
}
