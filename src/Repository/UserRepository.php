<?php

namespace App\Repository;

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
            //throw new InvalidArgumentException('User by given ID does not exist.');
            return;
        }

        $lecture = $this->getEntityManager()->getRepository(Lecture::class)->find($idLecture);

        if (empty($lecture)) {
            //throw new InvalidArgumentException('Lecture by given ID does not exist.');
            return;
        }

        if ($user->getLectures()->contains($lecture)) {
            //throw new InvalidArgumentException('User is already registered to given lecture.');
            return;
        }

        if ($lecture->getSlots() <= $lecture->getSlotsOccupied()) {
            //throw new InvalidArgumentException('Lecture by given ID has no free slots.');
            return;
        }

        if (!$user->getSpecialisations()->contains($lecture->getSpecialisation())) {
            //throw new InvalidArgumentException('User has no access to given lecture.');
            return;
        }

        $specialisation = $user->getSpecialisations()->get(
            $user->getSpecialisations()->indexOf($lecture->getSpecialisation())
        );

        $userLectures = $user->getLectures()->filter(function (Lecture $lecture) use ($specialisation) {
            return $lecture->getSpecialisation()->getIdSpecialisation() === $specialisation->getIdSpecialisation();
        });

        $lecturesEcts = $userLectures->map(function (Lecture $lecture) {
            return $lecture->getEcts();
        });

        if ($lecturesEcts[0] >= $specialisation->getEctsLimit()) {
            //throw new InvalidArgumentException('User has exceeded specialisation ECTS limit.');
            return;
        }

        $user->addLecture($lecture);
        $this->getEntityManager()->flush();
    }
}
