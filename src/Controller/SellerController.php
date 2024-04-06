<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/seller', name: 'app_seller_'), IsGranted('ROLE_SELLER')]
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
}
