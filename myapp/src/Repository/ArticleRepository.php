<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;


    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct($registry, Article::class);
    }

    public function saveArticle(Article $article): void
    {
        $this->manager->persist($article);
        $this->manager->flush();
    }
    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getAll()
    {
        $qb = $this->createQueryBuilder('s');

        return $qb->getQuery()->getResult(AbstractQuery::HYDRATE_OBJECT);
    }

    /**
     * @param string|null $name
     *
     * @return int|mixed|string
     */
    public function getAllAsArray(string $name = null)
    {
        $qb = $this->createQueryBuilder('s');

        if ($name) {
            $qb->where('s.name = :name')->setParameter('name', $name);
        }

        return $qb->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);
    }

    /**
     * @param string|null $name
     *
     * @return int
     */
    public function getPaginatedCount(?string $name = null): int
    {
        $qb = $this->createQueryBuilder('s')->select('COUNT(s)');

        if ($name) {
            $qb->where('s.name = :name')->setParameter('name', $name);
        }

        return $qb->getQuery()->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }

}
