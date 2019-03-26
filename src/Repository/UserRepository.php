<?php

namespace App\Repository;

use App\Entity\Lecture;
use App\Entity\User;
use App\Utils\RegistrationErrorLogger;
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
     * @var RegistrationErrorLogger
     */
    private $logger;

    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param RegistrationErrorLogger $logger
     */
    public function __construct(ManagerRegistry $registry, RegistrationErrorLogger $logger)
    {
        parent::__construct($registry, User::class);

        $this->logger = $logger;
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
            $this->logger->log("User (ID:{$idUser}) does not exist.");

            return;
        }

        $lecture = $this->getEntityManager()->getRepository(Lecture::class)->find($idLecture);

        if (empty($lecture)) {
            $this->logger->log("Lecture (ID:{$idLecture}) does not exist.");

            return;
        }

        if ($user->getLectures()->contains($lecture)) {
            $this->logger->log("User (ID:{$idUser}) is already registered to lecture (ID:{$idLecture}).");

            return;
        }

        if ($lecture->getSlots() <= $lecture->getSlotsOccupied()) {
            $this->logger->log("Lecture (ID:{$idLecture}) has no free slots.");

            return;
        }

        if (!$user->getSpecialisations()->contains($lecture->getSpecialisation())) {
            $this->logger->log("User (ID:{$idUser}) has no access to lecture (ID:{$idLecture}).");

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
            $this->logger->log("User (ID:{$idUser}) has exceeded specialisation (ID:{$specialisation->getIdSpecialisation()}) ECTS limit.");

            return;
        }

        $user->addLecture($lecture);
        $this->getEntityManager()->flush();
    }
}
