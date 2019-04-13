<?php

namespace App\Handler;

use App\Entity\SubscribeRequest;
use App\Service\SubscriptionService;
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
     * @var SubscriptionService
     */
    private $subscriptionService;

    /**
     * SubscribeRequestHandler constructor.
     *
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @param SubscribeRequest $request
     */
    public function __invoke(SubscribeRequest $request): void
    {
        $this->subscriptionService->subscribe($request->idLecture, $request->idUser, $request->idEnrollment);
    }
}
