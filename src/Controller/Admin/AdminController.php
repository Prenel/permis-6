<?php

namespace App\Controller\Admin ;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class AdminController extends AbstractController
{
    #[Route('/admin/category', name: 'category_view')]
    public function categoryView(): Response
    {
        return $this->render('admin/category.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/category/list', name: 'category_list')]
    public function categoryList(Request $request, CategoryRepository $categoryRepo): Response
    {   
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $filters = $request->query->all();
        
        $result = $categoryRepo->getPaginatedCategories($page, $limit, $filters);

        return $this->json(['result' => $result], 200,[],  ['groups' => 'category-read']);
    }

    #[Route('/admin/category/add', name: 'category_add')]
    public function categoryAdd(Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        
        if (empty($data['category']['name'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Veuillez remplir le nom de la catégorie.'
            ], Response::HTTP_BAD_REQUEST);    
        }
        if (empty($data['category']['subCategories'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Veuillez remplir au moin une sous-catégorie.'
            ], Response::HTTP_BAD_REQUEST);    
        }

        $category = new Category();
        foreach ($data['category']['subCategories'] as $dataSubCategory) {
            
            $subCategory = new SubCategory();
            $subCategory->setName($dataSubCategory['value']);
            $subCategory->setCreatedBy($user);
            $subCategory->setCreatedAt(new DateTimeImmutable());
            $em->persist($subCategory);
            $category->addSubCategory($subCategory);
        }
        $category->setName($data['category']['name']);
        $category->setCreatedBy($user);
        $category->setCreatedAt(new DateTimeImmutable());

        $em->persist($category);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'category' => $serializer->serialize($category, 'json',['groups' => 'category-read']),
            'message' => 'La catégorie a bien été créé.'
        ], Response::HTTP_CREATED);
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
