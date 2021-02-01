<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Services\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MaxiController extends AbstractController
{
    /**
     * @var ArticleService
     */
    private $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * @Route("/add-article", name="add_article", methods="post")
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function addArticle(Request $request): JsonResponse
    {
        $article =  $this->articleService->serializer->deserialize($request->getContent(),Article::class,'json');
        $content = json_decode($request->getContent(), true);
        $article = new Article();
        $article->setName($content['name']);
        $article->setDescription($content['description']);
        $validResult = $this->articleService->validateArticle($article);

        if ($validResult !== 'OK') {
            return new JsonResponse(['status' =>  $validResult], Response::HTTP_CREATED);
        }

        $this->articleService->articlesRepository->saveArticle($article);

        return new JsonResponse(['status' => $validResult], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get-article/{id}", name="get_one_article", methods="post")
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getArticle(int $id): JsonResponse
    {
        $article =  $this->articleService->articlesRepository->findOneBy(['id' => $id]);

        return new JsonResponse( $this->articleService->serializer->serialize($article,'json', ['groups' => 'get_article']), Response::HTTP_OK);
    }

    /**
     * @Route("/get-all-article", name="get_all_article", methods="post")
     *
     * @return JsonResponse
     */
    public function getAllArticle(): JsonResponse
    {
        $articles =  $this->articleService->articlesRepository->getAll();

        return new JsonResponse( $this->articleService->serializer->serialize($articles,'json', ['groups' => 'get_article']), Response::HTTP_OK);
    }

    /**
     * @Route("/article/list/{page}", methods={"post"}, name="get_articles_page")
     * @param string $page
     *
     * @return Response
     */
    public function list(string $page): Response
    {
        $response = $this->articleService->listArticles($page);
        $response->headers->set('Content-Type', 'application/json');

        return  $response;
    }
}