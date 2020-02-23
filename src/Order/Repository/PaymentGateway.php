<?php
declare(strict_types=1);

namespace App\Order\Repository;

use App\Order\Value\Money;
use App\Order\Value\AuthorizationNumber;

interface PaymentGateway
{
    public function charge(Money $money): AuthorizationNumber;
}
