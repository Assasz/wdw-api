<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Lecture
 *
 * @ORM\Table(name="lecture")
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
class Lecture
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_lecture", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idLecture;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="ects", type="integer", nullable=false)
     */
    private $ects;

    /**
     * @var string
     *
     * @ORM\Column(name="lecturer", type="string", length=255, nullable=false)
     */
    private $lecturer;

    /**
     * @var string
     *
     * @ORM\Column(name="auditorium", type="string", length=255, nullable=false)
     */
    private $auditorium;

    /**
     * @var string
     *
     * @ORM\Column(name="weekday", type="string", length=255, nullable=false)
     */
    private $weekday;

    /**
     * @var string
     *
     * @ORM\Column(name="week", type="string", length=0, nullable=false)
     */
    private $week;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hour", type="time", nullable=false)
     */
    private $hour;

    /**
     * @var int
     *
     * @ORM\Column(name="slots", type="integer", nullable=false)
     */
    private $slots;

    /**
     * @var int
     *
     * @ORM\Column(name="slots_occupied", type="integer", nullable=false)
     */
    private $slotsOccupied = '0';

    public function getIdLecture(): ?int
    {
        return $this->idLecture;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEcts(): ?int
    {
        return $this->ects;
    }

    public function setEcts(int $ects): self
    {
        $this->ects = $ects;

        return $this;
    }

    public function getLecturer(): ?string
    {
        return $this->lecturer;
    }

    public function setLecturer(string $lecturer): self
    {
        $this->lecturer = $lecturer;

        return $this;
    }

    public function getAuditorium(): ?string
    {
        return $this->auditorium;
    }

    public function setAuditorium(string $auditorium): self
    {
        $this->auditorium = $auditorium;

        return $this;
    }

    public function getWeekday(): ?string
    {
        return $this->weekday;
    }

    public function setWeekday(string $weekday): self
    {
        $this->weekday = $weekday;

        return $this;
    }

    public function getWeek(): ?string
    {
        return $this->week;
    }

    public function setWeek(string $week): self
    {
        $this->week = $week;

        return $this;
    }

    public function getHour(): ?string
    {
        return $this->hour->format('H:i');
    }

    public function setHour(\DateTimeInterface $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getSlots(): ?int
    {
        return $this->slots;
    }

    public function setSlots(int $slots): self
    {
        $this->slots = $slots;

        return $this;
    }

    public function getSlotsOccupied(): ?int
    {
        return $this->slotsOccupied;
    }

    public function setSlotsOccupied(int $slotsOccupied): self
    {
        $this->slotsOccupied = $slotsOccupied;

        return $this;
    }
}
