<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

final class CreateProduct
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
    }
}
