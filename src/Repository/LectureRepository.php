<?php

namespace App\Repository;

use App\Entity\Lecture;
use App\Entity\User;
use App\Utils\Logger\LoggerInterface;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * LectureRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param LoggerInterface $logger
     */
    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Lecture::class);

        $this->logger = $logger;
    }

    /**
     * Subscribes user to the lecture
     *
     * @param int $idLecture
     * @param int $idUser
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function subscribe(int $idLecture, int $idUser): void
    {
        $lecture = $this->find($idLecture);

        if (empty($lecture)) {
            $this->logger->log("[Subscribe] Lecture (ID:{$idLecture}) does not exist.");

            return;
        }

        $user = $this->getEntityManager()->getRepository(User::class)->find($idUser);

        if (empty($user)) {
            $this->logger->log("[Subscribe] User (ID:{$idUser}) does not exist.");

            return;
        }

        if ($user->getLectures()->contains($lecture)) {
            $this->logger->log("[Subscribe] User (ID:{$idUser}) is already subscribed to lecture (ID:{$idLecture}).");

            return;
        }

        if ($lecture->getSlots() <= $lecture->getSlotsOccupied()) {
            $this->logger->log("[Subscribe] Lecture (ID:{$idLecture}) has no free slots.");

            return;
        }

        if (!$user->getSpecialisations()->contains($lecture->getSpecialisation())) {
            $this->logger->log("[Subscribe] User (ID:{$idUser}) has no access to lecture (ID:{$idLecture}).");

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
            $this->logger->log("[Subscribe] User (ID:{$idUser}) has exceeded specialisation (ID:{$specialisation->getIdSpecialisation()}) ECTS limit.");

            return;
        }

        $lecture->addUser($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Unsubscribes user from the lecture
     *
     * @param int $idLecture
     * @param int $idUser
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function unsubscribe(int $idLecture, int $idUser): void
    {
        $lecture = $this->find($idLecture);

        if (empty($lecture)) {
            $this->logger->log("[Unsubscribe] Lecture (ID:{$idLecture}) does not exist.");

            return;
        }

        $user = $this->getEntityManager()->getRepository(User::class)->find($idUser);

        if (empty($user)) {
            $this->logger->log("[Unsubscribe] User (ID:{$idUser}) does not exist.");

            return;
        }

        if (!$user->getLectures()->contains($lecture)) {
            $this->logger->log("[Unsubscribe] User (ID:{$idUser}) is not subscribed to lecture (ID:{$idLecture}).");

            return;
        }

        $lecture->removeUser($user);
        $this->getEntityManager()->flush();
    }
}
