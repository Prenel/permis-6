<?php

namespace App\Controller;

use LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods:['POST'] )]
    public function index(): Response
    {
        throw new LogicException('cette méthode ne devrait pas appelé directement');
    }

    #[Route('/login', name: 'app_login', methods:['GET'] )]
    public function login(): Response
    {
        return $this->render('login/login.html.twig');
    }


}
