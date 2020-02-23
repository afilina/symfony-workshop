<?php
declare(strict_types=1);

namespace App\Tests\Product\Controller;

use App\Product\Controller\ListProducts;
use App\Product\Repository\ProductRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

/** @covers \App\Product\Controller\ListProducts */
final class ListProductsTest extends TestCase
{
    const PRODUCTS = [
        ['title' => 'Products 1'],
        ['title' => 'Products 2'],
    ];

    /** @var ListProducts */
    private $controller;
    /** @var MockObject|ProductRepository */
    private $productRepository;
    /** @var MockObject|Twig */
    private $templating;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->templating = $this->createMock(Twig::class);

        $this->controller = new ListProducts(
            $this->templating,
            $this->productRepository,
        );
    }

    public function testRendersProducts()
    {
        $this->productRepository
            ->method('getListData')
            ->willReturn(self::PRODUCTS);

        $this->templating
            ->expects($this->once())
            ->method('render')
            ->with(
                $this->isType('string'),
                ['items' => self::PRODUCTS]
            );

        self::assertInstanceOf(
            Response::class,
            $this->controller->handle(new Request())
        );
    }
}
