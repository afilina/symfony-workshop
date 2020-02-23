<?php
declare(strict_types=1);

namespace App\ShoppingCart\Cart;

use App\Entity\Product;
use App\Product\Repository\ProductRepository;
use App\ShoppingCart\Value\CartItem;
use App\ShoppingCart\Value\Quantity;
use App\ShoppingCart\View\CartItemView;
use App\Product\Value\ProductCode;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SessionCart implements Cart
{
    private SessionInterface $session;
    private ProductRepository $productRepository;
    /** @var CartItem[] */
    private array $cartItems = [];

    public function __construct(
        SessionInterface $session,
        ProductRepository $productRepository
    )
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    private function save()
    {
    }

    public function addItem(ProductCode $productCode): void
    {
        $quantity = Quantity::fromInteger(1);
        $existingItem = $this->cartItems[(string) $productCode] ?? null;

        if ($existingItem === null) {
            $itemToAdd = CartItem::fromValues($productCode, $quantity);
        } else {
            $itemToAdd = $existingItem->incrementQuantity($quantity);
        }

        $this->cartItems[(string) $productCode] = $itemToAdd;
        $this->save();
    }

    /**
     * @inheritDoc
     */
    public function getItemProducts()
    {
        $products = $this->productRepository->getListByCodes(
            $this->stringToProductCodes(array_keys($this->cartItems))
        );

        return $this->cartItemsToViewItems($products, $this->cartItems);
    }

    /**
     * @param array[] $itemsArray
     * @return CartItem[]
     */
    protected function arrayToItems(array $itemsArray): array
    {
        $items = [];
        foreach ($itemsArray as $code => $quantity) {
            $items[$code] = CartItem::fromValues(
                ProductCode::fromString((string)$code),
                Quantity::fromInteger((int)$quantity)
            );
        }
        return $items;
    }

    /**
     * @param string[] $codes
     * @return ProductCode[]
     */
    protected function stringToProductCodes(array $codes): array
    {
        return array_map(function (string $code) {
            return ProductCode::fromString($code);
        }, $codes);
    }

    /**
     * @param Product[] $products
     * @param CartItem[] $cartItems
     * @return CartItemView[]
     */
    protected function cartItemsToViewItems(array $products, array $cartItems): array
    {
        return array_map(function (Product $product, CartItem $item) {
            return CartItemView::fromValues(
                $item->getQuantity()->toInteger(),
                $product->name,
                $product->price
            );
        }, $products, $cartItems);
    }
}
