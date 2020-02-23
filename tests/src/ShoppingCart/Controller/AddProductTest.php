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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/** @covers \App\ShoppingCart\Controller\AddProduct */
class AddProductTest extends TestCase
{
    const REDIRECT_URL = '/redirect-url';

    private AddProduct $controller;
    /** @var MockObject|Cart */
    private $cart;
    /** @var MockObject|UrlGeneratorInterface */
    private $urlGenerator;

    protected function setUp(): void
    {
        $this->cart = $this->createMock(Cart::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $this->controller = new AddProduct($this->cart, $this->urlGenerator);
    }

    public function testHandle(): void
    {
        $code = ProductCode::fromString('123456');
        $this->cart
            ->expects($this->once())
            ->method('addItem')
            ->with($code);

        $this->expectRedirectToRoute('view_cart');

        $response = $this->controller->handle(
            Request::create('', 'POST', ['code' => (string) $code])
        );

        self::assertInstanceOf(
            RedirectResponse::class,
            $response
        );
    }

    /**
     * @param string $routeName
     */
    protected function expectRedirectToRoute(string $routeName): void
    {
        // TODO: we probably want to extract this common behavior to a ControllerTestCase
        $this->urlGenerator
            ->expects($this->once())
            ->method('generate')
            ->with($routeName)
            ->willReturn(self::REDIRECT_URL);
    }
}
