<?php
declare(strict_types=1);

namespace App\Product\Controller;

use App\Product\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Environment as Twig;

final class ListProducts
{
    private Twig $templating;
    private ProductRepository $productRepository;
    private CacheInterface $cache;

    public function __construct(
        Twig $templating,
        ProductRepository $productRepository,
        CacheInterface $cache
    )
    {
        $this->templating = $templating;
        $this->productRepository = $productRepository;
        $this->cache = $cache;
    }

    public function handle(Request $request): Response
    {
        if (in_array('application/json', $request->getAcceptableContentTypes())) {
            $listData = $this->productRepository->getListData();
            return new JsonResponse($listData);
        }

        $htmlList = $this->cache->get('products.list.all.html', function (ItemInterface $cacheItem) {
            $listData = $this->productRepository->getListData();
            return $this->templating->render(
                'product/list.html.twig',
                ['items' => $listData]
            );
        });

        return new Response($htmlList);
    }
}
