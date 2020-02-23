<?php
declare(strict_types=1);

namespace App\Tests\ShoppingCart\Controller;

use App\Product\Value\ProductCode;
use App\ShoppingCart\Cart\Cart;
use App\ShoppingCart\Controller\AddProduct;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/** @covers \App\ShoppingCart\Controller\AddProduct */
class AddProductTest extends TestCase
{
    private AddProduct $controller;
    /**
     * @var MockObject|Cart
     */
    private $cart;

    protected function setUp(): void
    {
        $this->cart = $this->createMock(Cart::class);
        $this->controller = new AddProduct($this->cart);
    }

    public function testHandle(): void
    {
    }
}
