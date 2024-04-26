<?php

namespace App\Controller;

use App\Entity\MediaSeller;
use App\Entity\User;
use App\Entity\UserSeller;
use App\Form\MediaSellerType;
use App\Form\UserSellerType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\UserSellerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/seller', name: 'app_seller_')]
#[IsGranted('ROLE_SELLER')]

class SellerController extends AbstractController
{
    // USER INFO PAGE
    // #[Route('/', name: 'index')]
    // public function index(UserRepository $user, UserSellerRepository $seller): Response
    // {
    //      $user = $this->$seller->find();
    //      $infos = $this->getUser()->getUserIdentifier();
    //      if(empty($infos)) {
    //          // vers le create profil_seller
    //      }
    //     return $this->render('seller/index.html.twig', [
    //         'user' => $user,
    //         'info' => $infos
    //     ]);
    // }

    #[Route('/', name: 'index')]
    public function index(Security $security, UserSellerRepository $sellerRepository, UserRepository $user): Response
    {
        $user = $security->getUser(); // Obtenez l'utilisateur actuellement connecté

        if (!$user) {

            // Gérer le cas où l'utilisateur n'est pas connecté
            echo ("il faut se connecter");
        }

        // Recherchez le profil vendeur associé à cet utilisateur
        $seller = $sellerRepository->findOneBy(['user' => $user]);

        if (!$seller) {
            // Gérer le cas où le profil vendeur n'existe pas pour cet utilisateur
            return $this->redirectToRoute('app_seller_seller_profil_create', ['id' => $user->getId()]);
        }

        $infos = $user->getUserIdentifier(); // Ou $user->getEmail(), $user->getUsername(), etc. selon votre configuration

        return $this->render('seller/index.html.twig', [
            'user' => $user,
            'seller' => $seller,
            'info' => $infos,
            'medias' => $seller->getMediaSellers(),

        ]);
    }


    // CREATE USER SELLER PROFIL
    #[Route('/create', name: 'seller_profil_create')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $userSeller = new UserSeller();
        $userSeller->setUser($user); // Associer l'utilisateur à l'entité UserSeller

        $form = $this->createForm(UserSellerType::class, $userSeller);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($userSeller);
            $manager->flush();

            $this->addFlash('success', 'Votre profil a été créé.');
            return $this->redirectToRoute('app_seller_media_create', ['id' => $userSeller->getId()]);
        }

        return $this->render('seller/seller_profil.html.twig', [
            'userSeller' => $userSeller,
            'form' => $form,
            'message' => 'Ajout'
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserSeller $userSeller, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserSellerType::class, $userSeller);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_seller_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seller/seller_profil.html.twig', [
            'user_seller' => $userSeller,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}/edit/email', name: 'edit_email', methods: ['GET', 'POST'])]
    // public function edit_email(User $user, Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);
    
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();
    
    //         return $this->redirectToRoute('app_seller_index', [], Response::HTTP_SEE_OTHER);
    //     }
    
    //     return $this->render('', [
    //         'user' => $user,
    //         'form' => $form->createView(),
    //     ]);
    // }




    // ADD MEDIA TO THE USER SELLER PROFIL
    #[Route('/media/create', name: 'media_create')]
    public function media_create(Request $request, EntityManagerInterface $manager, UserSellerRepository $userSellerRepository): Response
    {

        $user = $this->getUser();
        $userSeller = $userSellerRepository->findBy(['user' => $user->getId()])[0];

        $media = new MediaSeller();
    
        $form = $this->createForm(MediaSellerType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $files = $form->get('filePath')->getData();
            $nbMedias = count($userSeller->getMediaSellers());

            $file_name = date('Y-d-d-H-i-s') . '-' . $userSeller->getCompanyName() . ($nbMedias + 1) . '.' . $files->getClientOriginalExtension();

            $files->move($this->getParameter('upload_dir'), $file_name);

            $media->setFilePath($file_name);
            $media->setDescription($userSeller->getCompanyName() . ($nbMedias));

            $manager->persist($media);
            $userSeller->addMediaSeller($media);
            $manager->persist($userSeller);
            $manager->flush();

            $this->addFlash('success', 'Média créé, vous pouvez en ajouter un autre et valider ou cliquer sur terminé pour voir le détail');
            return $this->redirectToRoute('app_adress_new', ['id' => $userSeller->getId()]);

        }

        return $this->render('seller/media_create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // UPDATE MEDIA USER SELLER PROFIL
    // #[Route('/update/media/{id}', name: 'update_media')]
    // public function seller_update_media(User $product):Response
    // {        

    //     return $this->render('product/product_update_medias.html.twig',[
    //         'product'=>$product
    //     ]);

    // }

    // DELETE ALL MEDIA OF USER SELLER PROFIL
    // #[Route('/media/delete', name: 'media_delete')]
    // public function product_delete_medias(Request $request, EntityManagerInterface $manager, MediaSellerRepository $repository):Response
    // {
    //     $medias_id=$request->request->all()['medias'];

    //     if($medias_id):

    //         foreach ($medias_id as $media_id)
    //         {
    //             $media = $repository->find($media_id);
    //             $id=$media->getUserSeller()->getId();
    //             unlink($this->getParameter('upload_dir').'/'.$media->getFilePath());
    //             $manager->remove($media);
    //         }

    //         $manager->flush();
    //     endif;


    //     return $this->redirectToRoute('product_detail',['id'=>$id]);

    // }

    // DELETE ONE MEDIA
    // #[Route('/media/delete/{id}', name: 'media_delete')]
    // public function delete(UserSellerRepository $repo, EntityManagerInterface $manager, $id = null): Response
    // {
    //     if ($id) {
            
    //         $seller = $this->getUser();
    //         $userSeller = $repo->findBy(['user' => $seller->getId()])[0];
            
    //         $medias = $userSeller->getMediaSellers();
            
    //         foreach ($medias as $media) {
    //             unlink($this->getParameter('upload_dir') . '/' . $media->getFilePath());
    //             $manager->remove($media);
    //         }
    //         $manager->flush();
    //         $this->addFlash('success', 'Votre produit à était supprimé.');

    //         return $this->redirectToRoute('app_seller_index');
    //     }
    // }
    #[Route('/media/delete/{id}', name: 'media_delete')]
    public function delete(EntityManagerInterface $manager, $id = null): Response
    {
        // Vérifiez si un ID a été fourni dans la requête
        if ($id) {
            // Recherchez le média à supprimer
            $media = $manager->getRepository(MediaSeller::class)->find($id);
            
            // Vérifiez si le média existe
            if ($media) {
                // Supprimez le fichier associé au média
                unlink($this->getParameter('upload_dir') . '/' . $media->getFilePath());
    
                // Supprimez le média de la base de données
                $manager->remove($media);
                $manager->flush();
    
                $this->addFlash('success', 'Le média a été supprimé avec succès.');
            } else {
                $this->addFlash('error', 'Le média spécifié n\'existe pas.');
            }
        } else {
            $this->addFlash('error', 'Aucun ID de média spécifié pour la suppression.');
        }
    
        // Redirigez l'utilisateur vers une autre page
        return $this->redirectToRoute('app_seller_index');
    }
    

    // SHOW ALL MEDIAS IN GALLERIE PHOTOS

    // #[Route('/media/list', name: 'media_list')]
    // public function medias_list(UserSellerRepository $userSeller): Response
    // {
    //     $userSeller = $userSeller->findBy(["user" => $this->getUser()])[0];

    //     return $this->render('seller/media_gallery.html.twig', [
    //         'medias' => $userSeller->getMediaSellers()
    //     ]);
    // }



}
