<?php

namespace App\EventListener;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\Lecture;
use App\Entity\User;
use App\Repository\EnrollmentRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class AuthenticationSuccessListener
 *
 * @package App\EventListener
 */
class AuthenticationSuccessListener
{
    /**
     * @var EnrollmentRepository
     */
    private $enrollmentRepository;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var IriConverterInterface
     */
    private $iriConverter;

    /**
     * AuthenticationSuccessListener constructor.
     *
     * @param EnrollmentRepository $enrollmentRepository
     * @param NormalizerInterface $normalizer
     * @param IriConverterInterface $iriConverter
     */
    public function __construct(EnrollmentRepository $enrollmentRepository, NormalizerInterface $normalizer, IriConverterInterface $iriConverter)
    {
        $this->enrollmentRepository = $enrollmentRepository;
        $this->normalizer = $normalizer;
        $this->iriConverter = $iriConverter;
    }

    /**
     * @param AuthenticationSuccessEvent $event
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $data['user'] = $this->iriConverter->getIriFromItem($user);

        foreach ($this->enrollmentRepository->getActiveByUser($user->getId()) as $i => $enrollment) {
            $data['enrollments'][$i] = $this->normalizer->normalize($enrollment);
            $data['enrollments'][$i]['ectsLimit'] = $enrollment->getSpecialisation()->getEctsLimit();
            $data['enrollments'][$i]['ects'] = $enrollment->getLectures()
                ->filter(function (Lecture $lecture) use ($user) {
                    return $user->getLectures()->contains($lecture);
                })
                ->map(function (Lecture $lecture) {
                    return $lecture->getEcts();
                })[0];

            foreach ($enrollment->getLectures() as $j => $lecture) {
                $data['enrollments'][$i]['lectures'][$j] = $this->normalizer->normalize($lecture);
            }
        }

        $event->setData($data);
    }
}
