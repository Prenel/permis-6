<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getPaginatedCategories(int $page, int $limit, array $filters = []): array
    {
        $queryBuilder = $this->createQueryBuilder('c');

        // if (!empty($filters['name'])){
        //     $queryBuilder->andWhere('c.name LIKE :name')
        //         ->setParameter('name', '%' . $filters['name'] . '%');
        // } 

        $queryBuilder->setFirstResult( ($page - 1) * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($queryBuilder);
        
        $data = [];
        foreach ($paginator as $category){
            $data[] = $category; 
        }    

        return [
            'data' => $data,
            'total' => count($paginator),
            'page' => $page,
            'limit' => $limit
        ];
    }

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
