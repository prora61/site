<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route(path: '/admin', name: 'admin_start')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route(path: '/admin/users')]
    public function listUsers(): Response
    {
        return $this->json(null);
    }
}
