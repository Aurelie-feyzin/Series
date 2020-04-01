<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findCommentByAuthor(User $author): array
    {
        return $this->createQueryBuilder('c')
            ->select('c as comment, e.number as episodeNumber', 's.number as seasonNumber', 'p.slug as programSlug')
            ->leftJoin('c.episode', 'e')
            ->leftJoin('e.season', 's')
            ->leftJoin('s.program', 'p')
            ->andWhere('c.author = :author')
            ->setParameter('author', $author)
            ->orderBy('c.updatedAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findCommentByEpisode(Episode $episode): array
    {
        return $this->createQueryBuilder('c')
            ->select('c as comment', 'a as author')
            ->join('c.episode', 'e')
            ->join('c.author', 'a')
            ->andWhere('c.episode = :episode')
            ->setParameter('episode', $episode)
            ->orderBy('c.updatedAt', 'ASC')
            ->getQuery()
            ->execute();
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
