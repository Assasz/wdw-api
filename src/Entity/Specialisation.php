<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Specialisation
 *
 * @ORM\Table(name="specialisation")
 * @ORM\Entity
 *
 * @ApiResource(
 *     collectionOperations={
 *         "get"={
 *             "access_control"="is_granted('ROLE_USER')"
 *         }
 *     },
 *     itemOperations={
 *         "get"={
 *             "access_control"="is_granted('ROLE_USER')"
 *         }
 *     }
 * )
 */
class Specialisation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_specialisation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSpecialisation;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="studies_type", type="string", length=255, nullable=false)
     */
    private $studiesType;

    /**
     * @var string
     *
     * @ORM\Column(name="degree_course", type="string", length=255, nullable=false)
     */
    private $degreeCourse;

    /**
     * @var string
     *
     * @ORM\Column(name="semester", type="string", length=255, nullable=false)
     */
    private $semester;

    /**
     * @var string
     *
     * @ORM\Column(name="ects_limit", type="integer", nullable=false)
     */
    private $ectsLimit;

    /**
     * @return int|null
     */
    public function getIdSpecialisation(): ?int
    {
        return $this->idSpecialisation;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getStudiesType(): ?string
    {
        return $this->studiesType;
    }

    /**
     * @return null|string
     */
    public function getDegreeCourse(): ?string
    {
        return $this->degreeCourse;
    }

    /**
     * @return null|string
     */
    public function getSemester(): ?string
    {
        return $this->semester;
    }

    /**
     * @return int|null
     */
    public function getEctsLimit(): ?int
    {
        return $this->ectsLimit;
    }
}
