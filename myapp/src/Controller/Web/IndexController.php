<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route ("/", name="index", methods="get")
     */
    public function index(): Response
    {

        return $this->render(
            'index.twig',
            ['h1' => 'hello word']
        );
    }
}