<?php
declare(strict_types=1);

namespace App\ShoppingCart\Cart;

use App\Product\Value\ProductCode;
use App\ShoppingCart\View\CartItemView;

interface Cart
{
    public function addItem(ProductCode $productCode): void;
    /**
     * @return CartItemView[]
     */
    public function getItemProducts();
}
