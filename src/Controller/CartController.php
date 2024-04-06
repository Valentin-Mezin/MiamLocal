<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart_index')]
    public function cart(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart(),
            'total' => $cartService->getTotal()
        ]);
    }

    #[Route('/add-to-cart/{id}', name: 'app_cart_add')]
    public function addToCart(int $id, CartService $cartService): Response
    {
        $cartService->addToCart($id);
        return $this->redirectToRoute('app_index');
    }

    #[Route('/remove-from-cart/{id}', name: 'app_cart_remove')]
    public function removeFromCart(int $id, CartService $cartService): Response
    {
        $cartService->remove($id);
        return $this->redirectToRoute('app_cart_index');
    }
    
}