<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Episode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @method Episode|null find($id, $lockMode = null, $lockVersion = null)
 * @method Episode|null findOneBy(array $criteria, array $orderBy = null)
 * @method Episode[]    findAll()
 * @method Episode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EpisodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Episode::class);
    }

    public function findEpisodeByUrlParameter(ParameterBag $parameterBag): Episode
    {
        $programSlug = $parameterBag->get('programSlug');
        $seasonNumber = $parameterBag->get('seasonNumber');
        $episodeNumber = $parameterBag->get('episodeNumber');

        return $this->createQueryBuilder('e')
                ->innerJoin('e.season', 's')
                ->innerJoin('s.program', 'p')
                ->where('p.slug = :p_slug')
                ->andWhere('s.number = :s_number')
                ->andWhere('e.number = :e_number')
                ->setParameter('p_slug', $programSlug)
                ->setParameter('s_number', $seasonNumber)
                ->setParameter('e_number', $episodeNumber)
                ->getQuery()
                ->getOneOrNullResult();
    }

    // /**
    //  * @return Episode[] Returns an array of Episode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Episode
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
