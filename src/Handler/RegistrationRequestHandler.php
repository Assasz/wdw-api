<?php

namespace App\Handler;

use ApiPlatform\Core\Exception\InvalidArgumentException;
use App\Entity\Lecture;
use App\Entity\RegistrationRequest;
use App\Entity\User;
use App\Repository\LectureRepository;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class RegistrationRequestHandler
 *
 * Handles student registration to the lecture
 *
 * @package App\Handler
 */
class RegistrationRequestHandler implements MessageHandlerInterface
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
     * RegistrationRequestHandler constructor.
     *
     * @param LectureRepository $lectureRepository
     * @param UserRepository $userRepository
     */
    public function __construct(LectureRepository $lectureRepository, UserRepository $userRepository)
    {
        $this->lectureRepository = $lectureRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param RegistrationRequest $request
     */
    public function __invoke(RegistrationRequest $request)
    {
        $lecture = $this->lectureRepository->find($request->idLecture);

        if (empty($lecture) || !$lecture instanceof Lecture) {
            throw new InvalidArgumentException('Lecture by given ID does not exist.');
        }

        $user = $this->userRepository->find($request->idUser);

        if (empty($user) || !$user instanceof User) {
            throw new InvalidArgumentException('User by given ID does not exist.');
        }

        if ($lecture->getSlots() > $lecture->getSlotsOccupied()) {
            $user->addLecture($lecture);
        }
    }
}
