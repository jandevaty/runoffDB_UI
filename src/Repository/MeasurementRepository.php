<?php

namespace App\Repository;

use App\Entity\Measurement;
use App\Entity\Phenomenon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Measurement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Measurement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Measurement[]    findAll()
 * @method Measurement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeasurementRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Measurement::class);
    }

     /**
      * @return Measurement[] Returns an array of Measurement objects
      */
    public function findByPhenomenon(Phenomenon $phenomenon)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.phenomenon = :val')
            ->setParameter('val', $phenomenon)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function getPaginatorQuery(?array $filter, string $order, string $direction):QueryBuilder {
        $queryBuilder = $this->createQueryBuilder('measurement');
        $queryBuilder->leftJoin('measurement.phenomenon', 'p', 'WITH', 'measurement.phenomenon = p.id');
        $queryBuilder->leftJoin('measurement.locality', 'l', 'WITH', 'measurement.locality = l.id');
        if (isset($filter['dateFrom']) && $filter['dateFrom']) {
            $queryBuilder->andWhere($queryBuilder->expr()->gte('measurement.date',"'".$filter['dateFrom']->format("Y-m-d")."'"));
        }
        if (isset($filter['dateTo']) && $filter['dateTo']) {
            $queryBuilder->andWhere($queryBuilder->expr()->lte('measurement.date',"'".$filter['dateTo']->format("Y-m-d")."'"));
        }
        if (isset($filter['locality']) && $filter['locality']) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('measurement.locality',$filter['locality']->getId()));
        }
        if (isset($filter['phenomenon']) && $filter['phenomenon']) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('measurement.phenomenon',$filter['phenomenon']->getId()));
        }
        return $queryBuilder;
    }

}
