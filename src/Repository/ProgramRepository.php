<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Program;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder as QueryBuilderAlias;

/**
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    /**
     * Partial Query for find Programs and count nbActors, nbSeasons and nbEpisodes for each Program.
     */
    private function partialFindPrograms(): QueryBuilderAlias
    {
        return $this->createQueryBuilder('p')
            ->select('p as program', 'COUNT(DISTINCT a) as nbActors', 'COUNT(DISTINCT s) as nbSeasons', 'COUNT(DISTINCT e) as nbEpisodes')
            ->leftJoin('p.actors', 'a')
            ->leftJoin('p.seasons', 's')
            ->leftJoin('s.episodes', 'e')
            ->groupBy('p.id');
    }

    /**
     * Find all Programs from partialFindPrograms.
     */
    public function findAllForIndex(): array
    {
        $query = $this->partialFindPrograms()
            ->getQuery();

        return $query->execute();
    }

    /**
     * Find Programs by Category from partialFindPrograms.
     */
    public function findByCategories(Category $category): array
    {
        $query = $this->partialFindPrograms()
            ->andWhere('p.category = :category')
            ->setParameter('category', $category)
            ->getQuery();

        return $query->execute();
    }

    // /**
    //  * @return Program[] Returns an array of Program objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Program
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
