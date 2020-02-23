<?php
declare(strict_types=1);

namespace App\Order\Repository;

use App\Entity\Order;
use App\Order\Value\AuthorizationNumber;
use App\Order\Value\Money;
use App\Order\Value\OrderNumber;

final class StubPaymentGateway implements PaymentGateway
{
    public function charge(Money $money): AuthorizationNumber
    {
        return AuthorizationNumber::fromString('0123456789ABCDE');
    }
}
