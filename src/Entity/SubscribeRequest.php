<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SubscribeRequest
 *
 * @ApiResource(
 *     collectionOperations={
 *         "post"={
 *             "path"="/lectures/subscribe",
 *             "status"=202,
 *             "access_control"="is_granted('ROLE_USER')"
 *         }
 *     },
 *     itemOperations={},
 *     messenger=true,
 *     output=false
 * )
 */
final class SubscribeRequest
{
    /**
     * @var int
     *
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    public $idUser;

    /**
     * @var int
     *
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    public $idLecture;

    /**
     * @var int
     *
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    public $idEnrollment;
}
