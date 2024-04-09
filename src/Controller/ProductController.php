<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\UserSellerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product', name: 'app_product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'list_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    // ADD NEW PRODUCT
    #[route('/add', name: 'add_product')]

    public function add_product(Request $request, ProductRepository $repo, EntityManagerInterface $manager): Response
    {

        $product = new Product();
        $products = $repo->findAll();
        // dd($products);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $product->setSeller($this->getUser());
            $manager->persist($product);
            $manager->flush($product);
            $this->addFlash('success', 'Votre produit à était Créé.');
            // return $this->redirectToRoute('app');

        }
        return $this->render('product/add_product.html.twig', [
            'products' => $products,
            'form' => $form,
            'message' => 'Ajout'

        ]);
    }

}
