<?php
declare(strict_types=1);

namespace App\ShoppingCart\Controller;

use App\ShoppingCart\Cart\Cart;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AddProduct
{
    private Cart $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function handle(Request $request): Response
    {
    }
}
