<?php

namespace App\Controller;

use App\Entity\UserBuyer;
use App\Form\UserBuyerType;
use App\Repository\UserBuyerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/user/buyer')]
#[IsGranted('ROLE_BUYER')]

class UserBuyerController extends AbstractController
{
    #[Route('/', name: 'app_user_buyer_index', methods: ['GET'])]
    public function index(Security $security, UserBuyerRepository $userBuyerRepository): Response
    {
        $user = $security->getUser(); // Obtenez l'utilisateur actuellement connecté

        if (!$user) {

            // Gérer le cas où l'utilisateur n'est pas connecté
            echo ("il faut se connecter");
        }

        // Recherchez le profil vendeur associé à cet utilisateur
        $buyer = $userBuyerRepository->findOneBy(['user' => $user]);

        if (!$buyer) {
            // Gérer le cas où le profil vendeur n'existe pas pour cet utilisateur
            return $this->redirectToRoute('app_user_buyer_new', ['id' => $user->getId()]);
        }

        $infos = $user->getUserIdentifier(); // Ou $user->getEmail(), $user->getUsername(), etc. selon votre configuration

        return $this->render('user_buyer/index.html.twig', [
            'user' => $user,
            'user_buyer' => $buyer,
            'info' => $infos
            
        ]);
    }

    #[Route('/new', name: 'app_user_buyer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserBuyerRepository $buyerRepository): Response
    {
        $user = $this->getUser();
        $profil = $buyerRepository->findBy(['user' => $user->getId()]);

        if ($profil) {
            return $this->redirectToRoute('app_user_buyer_index');
        }
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $userBuyer = new UserBuyer();
        $userBuyer->setUser($user);

        $form = $this->createForm(UserBuyerType::class, $userBuyer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userBuyer);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été créé.');

            return $this->redirectToRoute('app_adress_new', ['id' => $userBuyer->getId()]);
        }

        return $this->render('user_buyer/new.html.twig', [
            'userBuyer' => $userBuyer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_buyer_show', methods: ['GET'])]
    public function show(UserBuyer $userBuyer): Response
    {
        return $this->render('user_buyer/show.html.twig', [
            'user_buyer' => $userBuyer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_buyer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserBuyer $userBuyer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserBuyerType::class, $userBuyer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_buyer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_buyer/edit.html.twig', [
            'user_buyer' => $userBuyer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_buyer_delete', methods: ['POST'])]
    public function delete(Request $request, UserBuyer $userBuyer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userBuyer->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($userBuyer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_buyer_index', [], Response::HTTP_SEE_OTHER);
    }
}
