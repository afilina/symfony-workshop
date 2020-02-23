<?php
declare(strict_types=1);

namespace App\ShoppingCart\Controller;

use App\Product\Value\ProductCode;
use App\ShoppingCart\Cart\Cart;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class AddProduct
{
    private Cart $cart;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        Cart $cart,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->cart = $cart;
        $this->urlGenerator = $urlGenerator;
    }

    public function handle(Request $request): Response
    {
        $productCode = ProductCode::fromString($request->get('code'));
        $this->cart->addItem($productCode);

        return new RedirectResponse($this->urlGenerator->generate('view_cart'));
    }
}
