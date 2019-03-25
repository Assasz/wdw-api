<?php

namespace App\Handler;

use App\Entity\RegistrationRequest;
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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * RegistrationRequestHandler constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param RegistrationRequest $request
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(RegistrationRequest $request)
    {
        $this->userRepository->registerToLecture($request->idUser, $request->idLecture);
    }
}
