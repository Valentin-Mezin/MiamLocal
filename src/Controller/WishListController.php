<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\WishList;
use App\Repository\WishListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/wishlist', name: 'app_wishlist_'), IsGranted("ROLE_BUYER")]
class WishListController extends AbstractController
{
    #[Route('/add/{id}', name: 'add')]
    public function addToWishList(Product $product, EntityManagerInterface $entinityManager): Response
    {
        $wish = new WishList();
        $wish->setUser($this->getUser());
        $wish->setProduct($product);

        $entinityManager->persist($wish);
        $entinityManager->flush();

        return $this->redirectToRoute('app_index');
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function removeFromWishList(
        Product $product,
        EntityManagerInterface $entinityManager,
        WishListRepository $wishListRepository
    ): Response
    {
        $wish = $wishListRepository->findOneBy([
            'user' => $this->getUser(),
            'product' => $product
        ]);
        if (!$wish) {
            throw $this->createNotFoundException('Wish not found');
        }
        $entinityManager->remove($wish);
        $entinityManager->flush();
        
        return $this->redirectToRoute('app_index');
    }
}