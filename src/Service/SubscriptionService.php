<?php

namespace App\Service;

use App\Repository\LectureRepository;
use App\Repository\UserRepository;
use App\Utils\Logger\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class SubscriptionService
 *
 * @package App\Service
 */
class SubscriptionService
{
    /**
     * @var LectureRepository
     */
    private $lectureRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SubscriptionService constructor.
     *
     * @param LectureRepository $lectureRepository
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(LectureRepository $lectureRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->lectureRepository = $lectureRepository;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * Subscribes user to the lecture
     *
     * @param int $idLecture
     * @param int $idUser
     */
    public function subscribe(int $idLecture, int $idUser): void
    {
        $lecture = $this->lectureRepository->find($idLecture);

        if (empty($lecture)) {
            $this->logger->log("[Subscribe] Lecture (ID:{$idLecture}) does not exist.");

            return;
        }

        $user = $this->userRepository->find($idUser);

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

        $lecture->addUser($user)->setSlotsOccupied($lecture->getSlotsOccupied() + 1);
        $this->entityManager->flush();
    }

    /**
     * Unsubscribes user from the lecture
     *
     * @param int $idLecture
     * @param int $idUser
     */
    public function unsubscribe(int $idLecture, int $idUser): void
    {
        $lecture = $this->lectureRepository->find($idLecture);

        if (empty($lecture)) {
            $this->logger->log("[Unsubscribe] Lecture (ID:{$idLecture}) does not exist.");

            return;
        }

        $user = $this->userRepository->find($idUser);

        if (empty($user)) {
            $this->logger->log("[Unsubscribe] User (ID:{$idUser}) does not exist.");

            return;
        }

        if (!$user->getLectures()->contains($lecture)) {
            $this->logger->log("[Unsubscribe] User (ID:{$idUser}) is not subscribed to lecture (ID:{$idLecture}).");

            return;
        }

        $lecture->removeUser($user)->setSlotsOccupied($lecture->getSlotsOccupied() - 1);
        $this->entityManager->flush();
    }
}
