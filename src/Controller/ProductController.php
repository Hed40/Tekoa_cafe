<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Products;
use App\Classe\Search;
use App\Form\SearchType;
use Symfony\Component\HttpFoundation\Request;
class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) 
    {
        $this->entityManager= $entityManager;
    }


    #[Route('/nos-produits', name: 'products')]

    public function index(Request $request): Response
    {
        //dd($products);
        $search = new Search();
        $form =$this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

       

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->entityManager->getRepository(Products::class )->findWithSearch($search);
        } else {
            $products = $this->entityManager->getRepository(Products::class )->findAll();
        }

        return $this->render('product/index.html.twig', [
            'product' => $products,
            'form' => $form->createView()
        ]);
    }

    #[Route('/produit/{slug}', name: 'product')]

    public function show($slug): Response
    {
        $product = $this->entityManager->getRepository(Products::class )->findOneBySlug($slug);

        if (!$product) {
            return $this->redirectToRoute(route:'products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}
