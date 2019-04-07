<?php

namespace App\Repository;

use App\Entity\Lecture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class LectureRepository
 *
 * @package App\Repository
 */
class LectureRepository extends ServiceEntityRepository
{
    /**
     * LectureRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lecture::class);
    }
}
