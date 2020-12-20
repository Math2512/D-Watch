<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="app_category")
     */
    public function index(CategoryRepository $repo): Response
    {
        $category = $repo->findBy(['user' => $this->getUser()], ['nom' => 'ASC']);
        
        return $this->render('category/index.html.twig', [
            'categories' => $category,
        ]);
    }
}
