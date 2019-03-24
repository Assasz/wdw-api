<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     messenger=true,
 *     collectionOperations={
 *         "post"={
 *             "status"=202,
 *             "access_control"="is_granted('ROLE_USER')"
 *         }
 *     },
 *     itemOperations={},
 *     output=false
 * )
 */
final class RegistrationRequest
{
    /**
     * @var string
     *
     * @Assert\NotBlank
     */
    public $index;

    /**
     * @var int
     *
     * @Assert\NotBlank
     */
    public $lectureId;
}
