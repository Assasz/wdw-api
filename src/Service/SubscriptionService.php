<?php

namespace App\Service;

use App\Entity\Enrollment;
use App\Entity\Lecture;
use App\Entity\User;
use App\Repository\EnrollmentRepository;
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
     * @var EnrollmentRepository
     */
    private $enrollmentRepository;

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
     * @param EnrollmentRepository $enrollmentRepository
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     */
    public function __construct(LectureRepository $lectureRepository, UserRepository $userRepository, EnrollmentRepository $enrollmentRepository, EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->lectureRepository = $lectureRepository;
        $this->userRepository = $userRepository;
        $this->enrollmentRepository = $enrollmentRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * Subscribes user to the lecture
     *
     * @param int $idLecture
     * @param int $idUser
     * @param int $idEnrollment
     */
    public function subscribe(int $idLecture, int $idUser, int $idEnrollment): void
    {
        $lecture = $this->lectureRepository->find($idLecture);

        if (!$lecture instanceof Lecture) {
            $this->logger->log("[Subscribe] Lecture (ID:{$idLecture}) does not exist.");

            return;
        }

        $user = $this->userRepository->find($idUser);

        if (!$user instanceof User) {
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

        $lectureEnrollment = $this->enrollmentRepository->find($idEnrollment);

        if (!$lectureEnrollment instanceof Enrollment) {
            $this->logger->log("[Subscribe] Enrollment (ID:{$idEnrollment}) does not exist.");

            return;
        }

        $userEnrollments = $this->enrollmentRepository->getActiveByUser($user->getId());
        $userHasAccess = false;

        foreach ($userEnrollments as $userEnrollment) {
            if ($userEnrollment->getId() === $lectureEnrollment->getId()) {
                $userHasAccess = true;

                break;
            }
        }

        if (!$userHasAccess || !$lectureEnrollment->getLectures()->contains($lecture)) {
            $this->logger->log("[Subscribe] User (ID:{$idUser}) has no access to lecture (ID:{$idLecture}).");

            return;
        }

        $lecturesEcts = $user->getLectures()
            ->filter(function (Lecture $lecture) use ($lectureEnrollment) {
                return $lecture->getEnrollments()->contains($lectureEnrollment);
            })
            ->map(function (Lecture $lecture) {
                return $lecture->getEcts();
            })[0];

        $specialisation = $lectureEnrollment->getSpecialisation();

        if ($specialisation->getEctsLimit() <= $lecturesEcts + $lecture->getEcts()) {
            $this->logger->log("[Subscribe] User (ID:{$idUser}) has exceeded specialisation (ID:{$specialisation->getId()}) ECTS limit.");

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

        if (!$lecture instanceof Lecture) {
            $this->logger->log("[Unsubscribe] Lecture (ID:{$idLecture}) does not exist.");

            return;
        }

        $user = $this->userRepository->find($idUser);

        if (!$user instanceof User) {
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
