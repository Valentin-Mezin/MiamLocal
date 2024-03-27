<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/buyer', name: 'app_buyer_')]
class BuyerController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('buyer/index.html.twig', [
            'controller_name' => 'BuyerController',
        ]);
    }
}
