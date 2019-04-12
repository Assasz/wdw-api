<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Enrollment
 *
 * @ORM\Table(name="enrollment")
 * @ORM\Entity(repositoryClass="App\Repository\EnrollmentRepository")
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
class Enrollment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_enrollment", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=false)
     */
    private $endDate;

    /**
     * @var Specialisation
     *
     * @ORM\OneToOne(targetEntity="Specialisation")
     * @ORM\JoinColumn(name="id_specialisation", referencedColumnName="id_specialisation")
     */
    private $specialisation;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Lecture", inversedBy="enrollments")
     * @ORM\JoinTable(name="enrollments_lectures",
     *      joinColumns={@ORM\JoinColumn(name="id_enrollment", referencedColumnName="id_enrollment")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_lecture", referencedColumnName="id_lecture")}
     * )
     */
    private $lectures;

    /**
     * Enrollment constructor.
     */
    public function __construct()
    {
        $this->lectures = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @return Specialisation|null
     */
    public function getSpecialisation(): ?Specialisation
    {
        return $this->specialisation;
    }

    /**
     * @return Collection|Lecture[]
     */
    public function getLectures(): Collection
    {
        return $this->lectures;
    }
}
