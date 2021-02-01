<?php

namespace App\Services;

use App\ConstantsDirectory\PaginationConstants;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleService
{
    public $articlesRepository;
    /**
     * @var SerializerInterface
     */
    public $serializer;

    /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var ValidatorInterface
     */
    public $validator;

    public function __construct(ArticleRepository $articlesRepository, SerializerInterface $serializer, PaginatorInterface $paginator, ValidatorInterface $validator)
    {
        $this->articlesRepository = $articlesRepository;
        $this->serializer = $serializer;
        $this->paginator = $paginator;
        $this->validator = $validator;
    }

    /**
     * @param string $page
     * @param int $itemPerPage
     *
     * @return Response
     */
    public function listArticles(string $page, int $itemPerPage = PaginationConstants::DEFAULT_ITEM_PER_PAGE): Response
    {
        $articles = $this->articlesRepository->getAllAsArray();
        $articles = $this->paginator->paginate($articles, $page, $itemPerPage);
        $totalItem = $articles->getTotalItemCount();
        $totalPage = (ceil($totalItem/$itemPerPage));

        $data = [
            'totalPage' => $totalPage,
            'totalItem' => $totalItem,
            'itemPerPage'=> $itemPerPage,
            'currentPage' => $articles->getCurrentPageNumber(),
            'data' => (array) $articles->getItems()
        ];

        return new Response($this->serializer->serialize($data, 'json'));
    }

    public function validateArticle(Article $article): string
    {
        $errors = $this->validator->validate($article);

            if (count($errors) > 0) {
                $errorsString = $errors->get(0)->getMessage();
                $errorsString .= $errors->has(1) ? $errors->get(1)->getMessage() : '' ;

                return 'ERROR: ' . $errorsString;
            }

        return 'OK';
    }
}