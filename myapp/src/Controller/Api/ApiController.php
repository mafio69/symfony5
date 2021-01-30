<?php

namespace App\Controller\Api;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/api/index", name="apiIndex", methods="post")
     */
    public function hello(): Response
    {
        return $this->json([
            'message' => 'welcome',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }

    /**
     * @Route("/api/test", name="api", methods="post")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ]);
    }


//    /**
//     * @Route("/api/article", name="add_article", methods={["POST"]})
//     * @param Request $request
//     * @return JsonResponse
//     */
//    public function addArticle(Request $request): JsonResponse
//    {
//        $data = json_decode($request->getContent(), true);
//        $article = new Article();
//        $article->setName($data['name']);
//        $article->setDescription($data['description']);
//
//        if (empty($article->getName()) || empty($article->getDescription())) {
//            throw new NotFoundHttpException('Expecting mandatory parameters!');
//        }
//
//        $this->articlesRepository->saveArticle($article);
//
//        return new JsonResponse(['status' => 'Customer created!'], Response::HTTP_CREATED);
//    }
}
