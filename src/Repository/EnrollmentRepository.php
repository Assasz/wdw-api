<?php

namespace App\Repository;

use ApiPlatform\Core\Exception\InvalidArgumentException;
use App\Entity\Enrollment;
use App\Entity\Specialisation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class EnrollmentRepository
 *
 * @package App\Repository
 */
class EnrollmentRepository extends ServiceEntityRepository
{
    /**
     * EnrollmentRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enrollment::class);
    }

    /**
     * Returns active user enrollments
     *
     * @param User $user
     * @return array
     * @throws InvalidArgumentException
     */
    public function getActiveByUser(User $user): array
    {
        $specialisationsIds = $user->getSpecialisations()->map(function (Specialisation $specialisation) {
            return $specialisation->getId();
        })[0];

        return $this->createQueryBuilder('e')
            ->join('e.specialisation', 's')
            ->where('s.id IN (:specialisationsIds)')
            ->andWhere('e.startDate <= CURRENT_TIMESTAMP()')
            ->andWhere('e.endDate > CURRENT_TIMESTAMP()')
            ->setParameter('specialisationsIds', $specialisationsIds)
            ->getQuery()
            ->getResult();
    }
}
