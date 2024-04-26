<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Repository\UserSellerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(UserRepository $userRepository, ProductRepository $productRepository, CategoryRepository $categoryRepository, UserSellerRepository $userSellerRepository): Response
    // {
    //     $categories = $categoryRepository->findAll();
    //     $products = $productRepository->findAll();
    //     $userSellers = $userSellerRepository->findAll();
    //     $users = $userRepository->findAll();

    //     return $this->render('index/index.html.twig', [
    //         'controller_name' => 'IndexController',
    //         'products' => $products,
    //         'categories' => $categories,
    //         'userSellers' => $userSellers,
    //         'users' => $users
    //     ]);
    // }
    // IndexController.php

 
 {
        // Récupérer tous les utilisateurs vendeurs
        $users = $userSellerRepository->findAll();
    
        // // Récupérer les produits associés à chaque utilisateur vendeur
        // $productsByUserSeller = [];
        // foreach ($users as $user) {
        //     $productsByUserSeller[$user->getId()] = $productRepository->findBy(['seller' => $user]);
        // }
    
        return $this->render('index/index.html.twig', [
            'users' => $users,
            // dd($users)
        ]);
    }
}




