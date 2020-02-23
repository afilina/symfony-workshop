<?php
declare(strict_types=1);

namespace App\Order\Repository;

use App\Entity\Order;
use App\Order\Value\OrderNumber;

final class StubOrderRepository implements OrderRepository
{
    public function getByNumber(OrderNumber $number): Order
    {
        $order = new Order();
        $order->number = '0123456789';
        return $order;
    }

    public function update(Order $order): void
    {
    }
}
