<?php

namespace App\Controller\Admin ;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/category', name: 'category_list')]
    public function categoryList(): Response
    {
        return $this->render('admin/category.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/category/add', name: 'category_add')]
    public function categoryAdd(): Response
    {
        return $this->render('admin/category.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/category/edit/{id}', name: 'category_edit')]
    public function categoryEdit(): Response
    {
        return $this->render('admin/category.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/category/delete', name: 'category_delete')]
    public function categoryDelete(): Response
    {
        return $this->render('admin/category.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
