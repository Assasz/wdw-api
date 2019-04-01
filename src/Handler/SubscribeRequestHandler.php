<?php

namespace App\Handler;

use App\Entity\SubscribeRequest;
use App\Repository\LectureRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class SubscribeRequestHandler
 *
 * Handles student subscription to the lecture
 *
 * @package App\Handler
 */
class SubscribeRequestHandler implements MessageHandlerInterface
{
    /**
     * @var LectureRepository
     */
    private $lectureRepository;

    /**
     * SubscribeRequestHandler constructor.
     *
     * @param LectureRepository $lectureRepository
     */
    public function __construct(LectureRepository $lectureRepository)
    {
        $this->lectureRepository = $lectureRepository;
    }

    /**
     * @param SubscribeRequest $request
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(SubscribeRequest $request)
    {
        $this->lectureRepository->subscribe($request->idLecture, $request->idUser);
    }
}
