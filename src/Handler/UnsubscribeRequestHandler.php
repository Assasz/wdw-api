<?php

namespace App\Handler;

use App\Entity\UnsubscribeRequest;
use App\Repository\LectureRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class UnsubscribeRequestHandler
 *
 * Handles student unsubscription from the lecture
 *
 * @package App\Handler
 */
class UnsubscribeRequestHandler implements MessageHandlerInterface
{
    /**
     * @var LectureRepository
     */
    private $lectureRepository;

    /**
     * UnsubscribeRequestHandler constructor.
     *
     * @param LectureRepository $lectureRepository
     */
    public function __construct(LectureRepository $lectureRepository)
    {
        $this->lectureRepository = $lectureRepository;
    }

    /**
     * @param UnsubscribeRequest $request
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(UnsubscribeRequest $request)
    {
        $this->lectureRepository->unsubscribe($request->idLecture, $request->idUser);
    }
}
