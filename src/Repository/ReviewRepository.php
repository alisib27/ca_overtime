<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

use function PHPUnit\Framework\throwException;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Review $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Review $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Review[] Returns an array of Review objects
    //  */

    public function findByHotelId($hotel_id, $start, $end)
    {
        if (strtotime($start) === false || strtotime($end) === false )
        {
            throw new \InvalidArgumentException('Wrong date format provided');
        }
            $start_date = date_create($start);
            $end_date = date_create($end);

            $from = new \DateTime($start_date->format("Y-m-d")." 00:00:00");
            $to = new \DateTime($end_date->format("Y-m-d")." 23:59:59");

            $date_range_in_days = date_diff($from, $to)->days;



        $query = $this->createQueryBuilder('r');

        switch ($date_range_in_days) {
            case $date_range_in_days > 89 :
                $query->select(
                    'count(r.score) as review_count, avg(r.score) as average_score,Month(r.created_at) AS date_range'
                );
                break;
            case $date_range_in_days > 29:
                $query->select(
                    'count(r.score) as review_count, avg(r.score) as average_score,Week(r.created_at) AS date_range'
                );
                break;
            case $date_range_in_days >= 0:
                $query->select(
                    'count(r.score) as review_count, avg(r.score) as average_score,Day(r.created_at) AS date_range'
                );
                break;
        }
        // This will return date range as the number of the day/week/month being processed,
        // this can be further enhanced depending on requirements
        $query->Where('r.created_at BETWEEN :from and :to')
            ->andWhere('r.hotel_id = :hotel_id')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->setParameter('hotel_id', $hotel_id)
            ->groupBy('date_range');

        return ($query->getQuery()->getResult());
    }


}
