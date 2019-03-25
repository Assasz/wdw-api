<?php

namespace App\Repository;

use ApiPlatform\Core\Exception\InvalidArgumentException;
use App\Entity\Lecture;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class UserRepository
 *
 * @package App\Repository
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Registers user to the lecture
     *
     * @param int $idUser
     * @param int $idLecture
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function registerToLecture(int $idUser, int $idLecture): void
    {
        $user = $this->find($idUser);

        if (empty($user)) {
            throw new InvalidArgumentException('User by given ID does not exist.');
        }

        $lecture = $this->getEntityManager()->getRepository(Lecture::class)->find($idLecture);

        if (empty($lecture)) {
            throw new InvalidArgumentException('Lecture by given ID does not exist.');
        }

        if ($lecture->getSlots() <= $lecture->getSlotsOccupied()) {
            throw new InvalidArgumentException('Lecture by given ID has no free slots.');
        }

        $user->addLecture($lecture);
        $this->getEntityManager()->flush();
    }
}
