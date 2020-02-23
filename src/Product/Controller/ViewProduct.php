<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\Repository\ProductRepository;
use App\Product\Value\ProductCode;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

final class ViewProduct
{
    private Twig $templating;
    private ProductRepository $productRepository;

    public function __construct(
        Twig $templating,
        ProductRepository $productRepository
    )
    {
        $this->templating = $templating;
        $this->productRepository = $productRepository;
    }

    public function handle(Request $request): Response
    {
        $productCode = ProductCode::fromString($request->get('code'));
        $product = $this->productRepository->getByCode($productCode);

        return new Response(
            $this->templating->render(
                'product/view.html.twig',
                ['item' => $product]
            )
        );
    }
}
