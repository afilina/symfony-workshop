<?php
declare(strict_types=1);

namespace App\Product\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

final class ListProducts
{
    private Twig $templating;

    public function __construct(Twig $templating)
    {
        $this->templating = $templating;
    }

    public function handle(): Response
    {
        $listData = [
            ['name' => 'The Outer Worlds'],
            ['name' => 'Factorio'],
        ];

        return new Response(
            $this->templating->render(
                'product/list.html.twig',
                ['items' => $listData]
            )
        );
    }
}
