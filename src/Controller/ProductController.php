<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id<\d+>}', name: 'app_product', methods: ["GET"])]
    public function view(int $id): Response
    {
       $randomNumber = rand(0,4);
       $data =[
        'id'=>$id,
       ];

       return $this->render('product/index.html.twig',[
        'product' =>$id,
        'number' => $randomNumber,
        'data' => $data,
       ]);
    }
}
