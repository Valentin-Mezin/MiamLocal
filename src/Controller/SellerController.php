<?php

namespace App\Controller;

use App\Entity\MediaSeller;
use App\Entity\UserSeller;
use App\Form\MediaSellerType;
use App\Form\UserSellerType;
use App\Repository\MediaSellerRepository;
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
        echo("il faut se connecter");
    }

    // Recherchez le profil vendeur associé à cet utilisateur
    $seller = $sellerRepository->findOneBy(['user' => $user]);

    if (!$seller) {
        // Gérer le cas où le profil vendeur n'existe pas pour cet utilisateur
        return $this->redirectToRoute('app_seller_seller_profil_create',['id' => $user->getId()]);
    }

    $infos = $user->getUserIdentifier(); // Ou $user->getEmail(), $user->getUsername(), etc. selon votre configuration

    return $this->render('seller/index.html.twig', [
        'user' => $user,
        'seller' => $seller,
        'info' => $infos
    ]);
}


    // CREATE USER SELLER PROFIL
    #[Route('/create/{id}', name: 'seller_profil_create')]
    public function create(Request $request, EntityManagerInterface $manager, UserRepository $userRepo, $id): Response
    {
        $user = $userRepo->find($id);

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





    // ADD MEDIA TO THE USER SELLER PROFIL
    #[Route('/media/create/{id}', name: 'media_create')]
    public function media_create(Request $request, EntityManagerInterface $manager, UserSellerRepository $userSellerRepo, $id):Response
    {
        $media = new MediaSeller();

        $form=$this->createForm(MediaSellerType::class, $media);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user=$userSellerRepo->find($id);

            $files=$form->get('filePath')->getData();
                $nbMedias=count($user->getMediaSellers());

                $file_name=date('Y-d-d-H-i-s').'-'.$user->getCompanyName().($nbMedias+1).'.'.$files->getClientOriginalExtension();

                $files->move($this->getParameter('upload_dir'), $file_name);

                $media->setFilePath($file_name);
                $media->setDescription($user->getCompanyName().($nbMedias));

                $manager->persist($media);
                $user->addMediaSeller($media);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Média créé, vous pouvez en ajouter un autre et valider ou cliquer sur terminé pour voir le détail');

        }

    return $this->render('seller/media_create.html.twig',[
        'form'=>$form->createView()
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
    #[Route('/media/delete/{id}', name: 'media_delete')]
    public function delete(UserSellerRepository $repo, EntityManagerInterface $manager, $id=null):Response
    {
        if($id){
        $product = $repo->find($id);
        $medias=$product->getMediaSellers();

        foreach ($medias as $media)
        {
            unlink($this->getParameter('upload_dir').'/'.$media->getFilePath());
                $manager->remove($media);
        }
        $manager->remove($product);
        $manager->flush();
        $this->addFlash('success', 'Votre produit à était supprimé.');
    
        return $this->redirectToRoute('app_seller_');
    
        }
    }   


    // SHOW ALL MEDIAS IN GALLERIE PHOTOS

    #[Route('/media/list', name: 'media_list')]
    public function medias_list(Security $security, MediaSellerRepository $repo, UserSellerRepository $userSeller): Response
    {
        // Récupérez l'utilisateur actuellement connecté
        // $user = $security->getUser();
        $currentUserId = $this->getUser()->getId();
        // dd($user);

        $userSellerId = $userSeller->findBy(["user" => $currentUserId]);
        // dd($userSellerId);

    
        // Vérifiez si l'utilisateur est connecté
        if (!$userSellerId) {
            // Redirigez ou traitez le cas où aucun utilisateur n'est connecté
        }
    
        // Récupérez l'entité UserSeller associée à l'utilisateur connecté
        // $userSeller = $user->getUserIdentifier();
    
        // Vérifiez si l'utilisateur a un UserSeller associé
        if (!$userSeller) {
            // Gérez le cas où l'utilisateur n'a pas de UserSeller associé
        }
    
        // Récupérez les médias associés à l'entité UserSeller de l'utilisateur connecté
        $medias = $repo->findBy(['userSeller' => $userSellerId]);

        // dump($medias);
        // die;
    
        return $this->render('seller/media_gallery.html.twig', [
            'medias' => $medias
        ]);
    }


    // ADD NEW PRODUCT

    

}



