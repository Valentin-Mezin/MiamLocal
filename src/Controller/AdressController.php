<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressType;
use App\Repository\AdressRepository;
use App\Repository\UserBuyerRepository;
use App\Repository\UserSellerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/adress')]
class AdressController extends AbstractController
{
    #[Route('/', name: 'app_adress_index', methods: ['GET'])]
    public function index(AdressRepository $adressRepository): Response
    {
        return $this->render('adress/index.html.twig', [
            'adresses' => $adressRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_adress_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserSellerRepository $userSellerRepository, UserBuyerRepository $userBuyerRepository): Response
    {
        $user = $this->getUser();

        $userRole = $user->getRoles()[0];

        $userSeller = null;
        $userBuyer = null;

        if($userRole === 'ROLE_SELLER') {
            $userSeller = $userSellerRepository->findBy(['user' => $user->getId()])[0];
            if ($userSeller->getAdress()) {
                return $this->redirectToRoute('app_seller_index');
            }
        }
        if($userRole === 'ROLE_BUYER') {
            $userBuyer = $userBuyerRepository->findBy(['user' => $user->getId()])[0];
            if ($userBuyer->getAdress()) {
                return $this->redirectToRoute('app_user_buyer_index');
            }
        }   
        
        // dd($userBuyer);


        $adress = new Adress();
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($adress);
            if ($userSeller !== null) {
                $userSeller->setAdress($adress);
                $entityManager->persist($userSeller);
            } elseif ($userBuyer !== null) {
                $userBuyer->setAdress($adress);
                $entityManager->persist($userBuyer);
            } else {
                // Gérer le cas où aucun utilisateur n'est trouvé
                // Peut-être rediriger l'utilisateur vers une page d'erreur
            }
            
            $entityManager->flush();
            if ($userBuyer) {
                return $this->redirectToRoute('app_user_buyer_index', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_seller_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('adress/new.html.twig', [
            'adress' => $adress,
            'form' => $form,
            'userSeller' => $userSeller,
            'userBuyer' => $userBuyer
        ]);
    }

    #[Route('/{id}', name: 'app_adress_show', methods: ['GET'])]
    public function show(Adress $adress): Response
    {
        return $this->render('adress/show.html.twig', [
            'adress' => $adress,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adress_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adress $adress, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adress/edit.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adress_delete', methods: ['POST'])]
    public function delete(Request $request, Adress $adress, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adress->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($adress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adress_index', [], Response::HTTP_SEE_OTHER);
    }
}
