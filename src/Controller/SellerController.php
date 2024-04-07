<?php

namespace App\Controller;

use App\Entity\MediaSeller;
use App\Entity\UserSeller;
use App\Form\MediaSellerType;
use App\Form\UserSellerType;
use App\Repository\UserRepository;
use App\Repository\UserSellerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/seller', name: 'app_seller_')]
#[IsGranted('ROLE_SELLER')]
class SellerController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        // $infos = $this->getUser()->getInfoSeller();
        // if(empty($infos)) {
        //     // vers le create infos_seller
        // }
        return $this->render('seller/index.html.twig', [
            'controller_name' => 'SellerController',
        ]);
    }

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
            return $this->redirectToRoute('media_create', ['id' => $userSeller->getId()]);
        }

        return $this->render('seller/seller_profil.html.twig', [
            'userSeller' => $userSeller,
            'form' => $form,
            'message' => 'Ajout'
    ]);
}

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


}
