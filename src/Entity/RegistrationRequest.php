<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RegistrationRequest
 *
 * @ApiResource(
 *     collectionOperations={
 *         "post"={
 *             "path"="/register_to_lecture",
 *             "status"=202,
 *             "access_control"="is_granted('ROLE_USER')"
 *         }
 *     },
 *     itemOperations={},
 *     messenger=true,
 *     output=false
 * )
 */
final class RegistrationRequest
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
}
