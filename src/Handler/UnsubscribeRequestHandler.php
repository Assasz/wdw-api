<?php

namespace App\Handler;

use App\Entity\UnsubscribeRequest;
use App\Service\SubscriptionService;
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
     * @var SubscriptionService
     */
    private $subscriptionService;

    /**
     * UnsubscribeRequestHandler constructor.
     *
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @param UnsubscribeRequest $request
     */
    public function __invoke(UnsubscribeRequest $request): void
    {
        $this->subscriptionService->unsubscribe($request->idLecture, $request->idUser);
    }
}
