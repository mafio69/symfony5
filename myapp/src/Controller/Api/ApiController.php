<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/index", name="api_index", methods="post")
     */
    public function hello(): Response
    {
        return $this->json([
            'message' => 'Welcome in test Api by Mafio',
        ]);
    }
}
