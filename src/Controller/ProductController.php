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

#[Route('/product', name: 'app_product_')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    // ADD NEW PRODUCT
    #[route('/add', name: 'add')]

    public function add_product(Request $request, ProductRepository $repo, EntityManagerInterface $manager): Response
    {

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $product->setSeller($this->getUser());

            $file=$form->get('productMedia')->getData();
            
            $file_name=date('Y-d-d-H-i-s').'.'.$file->getClientOriginalExtension();
            
            $file->move($this->getParameter('upload_dir'), $file_name);

            $product->setProductMedia($file_name);

            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Votre produit à était Créé.');
            return $this->redirectToRoute('app_product_list');

        }
        return $this->render('product/add_product.html.twig', [
            'form' => $form,
            'message' => 'Ajout'
        ]);
    }

}
