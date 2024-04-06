<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private $session;

    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository
    ) {
        $this->session = $this->requestStack->getSession();
    }

    /**
     * Add a product to the cart
     */
    public function addTocart(int $id): void
    {
        $cart = $this->session->get('cart') ?? [];

        if (!empty($cart[$id])) $cart[$id];
        else $cart[$id] = 1;
    
        $this->session->set('cart', $cart);
    }

    /**
     * Get the cart content
     */
    public function getCart(): array
        
    {
        $cart = $this->session->get('cart') ?? [];

        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            if (!$product) continue;

                $cartWithData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'total' => $product->getPrice() * $quantity,
                ];
        }

        return $cartWithData;
    }

    /**
     * Get the total price of the cart
     */
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $total;
    }

    /**
     * Remove a product from the cart
     */
    public function remove(int $id): void
    {
        $cart = $this->session->get('cart') ?? [];

        unset($cart[$id]);
    
        $this->session->set('cart', $cart);
    }
}