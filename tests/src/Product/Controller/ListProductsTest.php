<?php
declare(strict_types=1);

namespace App\Tests\Product\Controller;

use App\Product\Controller\ListProducts;
use PHPUnit\Framework\TestCase;

/** @covers \App\Product\Controller\ListProducts */
final class ListProductsTest extends TestCase
{
    /** @var ListProducts */
    private $controller;

    protected function setUp(): void
    {
        $this->controller = new ListProducts();
    }

    public function testRendersProducts()
    {
    }
}
