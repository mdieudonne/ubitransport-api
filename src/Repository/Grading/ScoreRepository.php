<?php

namespace App\Repository\Grading;

use App\Entity\Grading\Score;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Score|null find($id, $lockMode = null, $lockVersion = null)
 * @method Score|null findOneBy(array $criteria, array $orderBy = null)
 * @method Score[]    findAll()
 * @method Score[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

    // /**
    //  * @return Score[] Returns an array of Score objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Score
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

  public function countAll() {
    return $this->createQueryBuilder('s')
      ->select('COUNT(s)')
      ->getQuery()
      ->getSingleScalarResult();
  }

  public function countAllByStudent(int $idStudent) {
    $qb = $this->createQueryBuilder('s')
      ->select('COUNT(s)');

    if ($idStudent !== 0) {
      $qb->andWhere('s.student = :idStudent')
        ->setParameter('idStudent', $idStudent);
    }

    return $qb->getQuery()->getSingleScalarResult();

  }

  public function findByBatchByStudent(string $limit, int $offset, int $idStudent)
  {
    $qb = $this->createQueryBuilder('s')
      ->select('s.id', 's.subject', 's.value')
      ->setMaxResults($limit)
      ->setFirstResult($offset);

    if ($idStudent !== 0) {
      $qb->andWhere('s.student = :idStudent')
        ->setParameter('idStudent', $idStudent);
    }

    return $qb->getQuery()->getResult();
  }
}
