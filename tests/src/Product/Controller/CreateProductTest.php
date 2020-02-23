<?php
declare(strict_types=1);

namespace App\Tests\Product\Controller;

use App\Entity\Product;
use App\Product\Controller\CreateProduct;
use App\Product\Repository\ProductRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Twig\Environment as Twig;

/** @covers \App\Product\Controller\CreateProduct */
class CreateProductTest extends TestCase
{
    private CreateProduct $controller;
    /** @var ProductRepository|MockObject */
    private $productRepository;
    /** @var MockObject|Twig */
    private $templating;
    /** @var MockObject|FormFactoryInterface */
    private $formFactory;
    /** @var MockObject|FormInterface */
    private $form;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->templating = $this->createMock(Twig::class);
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->form = $this->createMock(FormInterface::class);

        $this->formFactory
            ->method('create')
            ->willReturn($this->form);

        $this->controller = new CreateProduct(
            $this->templating,
            $this->productRepository,
            $this->formFactory
        );
    }

//    public function testHandle_WithoutInput_WillDisplayForm(): void
//    {
//    }
//
//    public function testHandle_WithCorrectInput_WillSaveAndRedirect(): void
//    {
//    }
//
//    public function testHandle_WithInvalidInput_WillNotSave(): void
//    {
//    }
//
//    public function testHandle_WithDuplicateCode_WillDisplayError(): void
//    {
//    }

    protected function setupHasInput(): void
    {
        $this->form
            ->method('isSubmitted')
            ->willReturn(true);
    }

    protected function setupInputInvalid(): void
    {
        $this->form
            ->method('isValid')
            ->willReturn(false);
    }

    protected function setupInputIsValid(): void
    {
        $this->form
            ->method('isValid')
            ->willReturn(true);
        $this->form
            ->method('getData')
            ->willReturn(new Product());
    }

    protected function expectSave(InvokedCount $matcher): void
    {
        $this->productRepository
            ->expects($matcher)
            ->method('create');
    }

    protected function expectCustomError(InvokedCount $matcher): void
    {
        $this->form
            ->expects($matcher)
            ->method('addError');
    }
}
