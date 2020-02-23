<?php
declare(strict_types=1);

namespace App\ShoppingCart\Controller;

use App\ShoppingCart\Cart\Cart;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

final class ViewCart
{
    private Cart $cart;
    private Twig $templating;

    public function __construct(Cart $cart, Twig $templating)
    {
        $this->cart = $cart;
        $this->templating = $templating;
    }

    public function handle(): Response
    {
        return new Response($this->templating->render('shopping-cart/view.html.twig', [
            'items' => $this->cart->getItemProducts(),
        ]));
    }
}
