<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Entity\Product;
use App\Product\Exception\DuplicateProduct;
use App\Product\Exception\DuplicateProductCode;
use App\Product\FormType\CreateProductType;
use App\Product\Repository\ProductRepository;
use App\Product\Value\ProductCode;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

final class CreateProduct
{
    private Twig $templating;
    private ProductRepository $productRepository;
    private FormFactoryInterface $formFactory;

    public function __construct(
        Twig $templating,
        ProductRepository $productRepository,
        FormFactoryInterface $formFactory
    )
    {
        $this->templating = $templating;
        $this->productRepository = $productRepository;
        $this->formFactory = $formFactory;
    }

    public function handle(Request $request): Response
    {
        $form = $this->formFactory->create(CreateProductType::class, new Product());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $product = $form->getData();
                $this->productRepository->create(
                    ProductCode::fromString($product->code),
                    $product
                );
            } catch (DuplicateProduct $duplicateProductCode) {
                $formError = new FormError($duplicateProductCode->getMessage());
                $form->addError($formError);
                return $this->createFormResponse($form);
            }

            return new RedirectResponse("/products");
        }

        return $this->createFormResponse($form);
    }

    protected function createFormResponse(FormInterface $form): Response
    {
        return new Response(
            $this->templating->render(
                'product/add.html.twig',
                ['form' => $form->createView()]
            )
        );
    }
}
