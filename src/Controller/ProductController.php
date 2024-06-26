<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\MediaType;
use App\Form\ProductType;
use App\Form\ProductUpdateType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
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
    #[Route('/seller/{id}', name: 'show_product_profil')]

    public function index(UserRepository $userRepository, ProductRepository $productRepository, UserSellerRepository $userSellerRepository, Request $request): Response
    {
        // Récupérer l'ID du vendeur à partir de la requête
        $userId = $request->query->get('id');
    
        // Recherche du vendeur correspondant à l'ID
        $userSeller = $userSellerRepository->find($userId);
        // dd($userSeller);


          // Vérifier si le vendeur existe
    if ($userSeller) {
        // Récupérer l'objet User associé à l'objet UserSeller
        $user = $userSeller->getUser();
        // Récupérer l'ID de l'utilisateur
        $products = $user->getProducts();
        // Utilisez $user comme nécessaire dans votre contrôleur
    } else {
        // Gérer le cas où le vendeur n'est pas trouvé
    }
    
    
    
    // Récupérer les produits associés à l'utilisateur connecté
    // $products = $productRepository->findBy(['seller' => $userId]);
    
    return $this->render('product/index.html.twig', [
        'products' => $products,
        // dd($products),
            'user' => $userId, // Passer l'ID du vendeur à la vue
            'seller' => $userSeller,
            'userSeller' => $userSellerRepository
        ]);
    }
    


    // ADD NEW PRODUCT : OK //
    #[route('/add', name: 'add')]

    public function add_product(Request $request, ProductRepository $repo, EntityManagerInterface $manager): Response
    {

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSeller($this->getUser());

            $file = $form->get('productMedia')->getData();

            $file_name = date('Y-d-d-H-i-s') . '.' . $file->getClientOriginalExtension();

            $file->move($this->getParameter('upload_dir'), $file_name);

            $product->setProductMedia($file_name);

            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'Votre produit à était Créé.');
            return $this->redirectToRoute('app_seller_index');
        }
        return $this->render('product/add_product.html.twig', [
            'form' => $form,
            'message' => 'Ajout'
        ]);
    }



    /// UPDATE PRODUCT : OK /////

    #[Route('/{$id}', name: 'update')]
    public function update(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProductUpdateType::class, $product);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();
            $manager->persist($product);
            $manager->flush();
            $this->addFlash('success', 'Votre produit à était modifié.');
            return $this->redirectToRoute('app_product_list');
        }

        return $this->render('product/update_product.html.twig', [
            // 'products' => $products,
            'form' => $form,
            'message' => 'Modification',
            'product' => $product
        ]);
    }




    // DELETE PRODUCT : OK //
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(ProductRepository $repo, EntityManagerInterface $manager, $id = null): Response
    {
        if ($id) {
            $product = $repo->find($id);
            $product->getProductMedia();
            unlink($this->getParameter('upload_dir') . '/' . $product->getProductMedia());
        }

        $manager->remove($product);
        $manager->flush();
        $this->addFlash('success', 'Votre produit à était supprimé.');

        return $this->redirectToRoute('app_product_list');
    }

    #[Route('/upload-file/{id}', name: 'upload_media')]
    public function updateMediaProduct(Product $product, Request $request, EntityManagerInterface $entityManager)
    {
        $oldMedia = $product->getProductMedia();
        $form = $this->createForm(MediaType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('media')->getData();

            $file_name = date('Y-d-d-H-i-s') . '.' . $file->getClientOriginalExtension();

            $file->move($this->getParameter('upload_dir'), $file_name);

            unlink($this->getParameter('upload_dir') . '/' .$oldMedia);

            $product->setProductMedia($file_name);

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Votre produit à était Créé.');
            return $this->redirectToRoute('app_product_list');
        }

        return $this->render('product/update-media.html.twig', [
            'product' => $product,
            'form' => $form
        ]);
    }
}
