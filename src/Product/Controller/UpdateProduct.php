<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\FormType\UpdateProductType;
use App\Product\Repository\ProductRepository;
use App\Product\Value\ProductCode;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

final class UpdateProduct
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
        $code = ProductCode::fromString($request->get('code'));
        $product = $this->productRepository->getByCode($code);

        $form = $this->formFactory->create(UpdateProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $this->productRepository->update(
                ProductCode::fromString($product->code),
                $product
            );
            return new RedirectResponse("/products");
        }

        return new Response(
            $this->templating->render(
                'product/edit.html.twig',
                ['form' => $form->createView(),]
            )
        );
    }
}
